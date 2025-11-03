<?php

namespace App\Exports\Penjual;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class OmsetExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $toko_id;
    protected $tanggal_mulai;
    protected $tanggal_selesai;

    public function __construct($toko_id, $tanggal_mulai, $tanggal_selesai)
    {
        $this->toko_id = $toko_id;
        $this->tanggal_mulai = $tanggal_mulai ? Carbon::parse($tanggal_mulai)->startOfDay() : null;
        $this->tanggal_selesai = $tanggal_selesai ? Carbon::parse($tanggal_selesai)->endOfDay() : null;
    }

    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        // Hanya ekspor transaksi yang "SELESAI"
        $query = Transaksi::query()
            ->where('toko_id', $this->toko_id)
            ->where('status_pesanan', 'selesai') // PENTING: Omset dihitung dari pesanan selesai
            ->with('user', 'details.produk'); // Ambil relasi

        if ($this->tanggal_mulai) {
            $query->where('updated_at', '>=', $this->tanggal_mulai); // Asumsi 'selesai' di-update di updated_at
        }
        if ($this->tanggal_selesai) {
            $query->where('updated_at', '<=', $this->tanggal_selesai);
        }

        return $query->orderBy('updated_at', 'desc');
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        // Judul kolom di file Excel
        return [
            'Invoice ID',
            'Tanggal Selesai',
            'Nama Pembeli',
            'Email Pembeli',
            'Total Harga (Rp)',
            'Metode Pembayaran',
            'Daftar Produk (Jumlah x Nama)',
        ];
    }

    /**
    * @param Transaksi $transaksi
    */
    public function map($transaksi): array
    {
        // Format data per baris
        $daftarProduk = $transaksi->details->map(function ($detail) {
            return $detail->jumlah . 'x ' . $detail->produk->nama_produk;
        })->implode('; '); // Pisahkan dengan semicolon

        return [
            $transaksi->invoice_id,
            Carbon::parse($transaksi->updated_at)->format('d-m-Y H:i'),
            $transaksi->user->name,
            $transaksi->user->email,
            $transaksi->total_harga,
            ucwords(str_replace('_', ' ', $transaksi->metode_pembayaran)),
            $daftarProduk,
        ];
    }
}
