<?php

namespace App\Exports\Penjual;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 

class OmsetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $tokoId;
    protected $tanggalMulai;
    protected $tanggalSelesai;

    public function __construct($tokoId, $tanggalMulai, $tanggalSelesai)
    {
        $this->tokoId = $tokoId;
        $this->tanggalMulai = $tanggalMulai;
        $this->tanggalSelesai = $tanggalSelesai;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Transaksi::where('toko_id', $this->tokoId)
                        ->where('status_transaksi', 'selesai')
                        ->with('user', 'detailTransaksis.produk');

        if ($this->tanggalMulai) {
            $query->where('updated_at', '>=', Carbon::parse($this->tanggalMulai)->startOfDay());
        }
        if ($this->tanggalSelesai) {
            $query->where('updated_at', '<=', Carbon::parse($this->tanggalSelesai)->endOfDay());
        }

        return $query->orderBy('updated_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Invoice ID',
            'Tanggal Selesai',
            'Nama Pembeli',
            'Email Pembeli',
            'Metode Pembayaran',
            'Daftar Produk (SKU - Nama)',
            'Total Qty',
            'Total Omset (Rp)',
            'Alamat Pengiriman',
        ];
    }

    /**
     * @var Transaksi $transaksi
     * @return array
     */
    public function map($transaksi): array
    {
        // 1. Format Daftar Produk
        $produkList = $transaksi->detailTransaksis->map(function ($detail) {
            // Cek biar ga error kalau produk dihapus
            $namaProduk = $detail->produk ? $detail->produk->nama_produk : 'Produk Terhapus';
            return $detail->jumlah . 'x ' . $namaProduk;
        })->implode("\n");

        $totalQty = $transaksi->detailTransaksis->sum('jumlah');

        // 2. Format Alamat (INI YANG BIKIN ERROR TADI)
        $alamat = $transaksi->alamat_pengiriman;

        // Cek: Jika datanya masih berupa String JSON, kita ubah jadi Array dulu
        if (is_string($alamat)) {
            $alamat = json_decode($alamat, true);
        }

        // Cek: Pastikan sekarang sudah jadi Array valid
        if (is_array($alamat)) {
            $fullAlamat = sprintf(
                "%s, %s, %s, %s, %s",
                $alamat['alamat_lengkap'] ?? '-',
                $alamat['kecamatan'] ?? '-',
                $alamat['kota_kabupaten'] ?? '-',
                $alamat['provinsi'] ?? '-',
                $alamat['kode_pos'] ?? '-'
            );
        } else {
            $fullAlamat = 'Alamat tidak valid';
        }

        return [
            $transaksi->invoice_id,
            $transaksi->updated_at->format('Y-m-d H:i:s'),
            $transaksi->user->name ?? 'User Terhapus', // Pakai ?? jaga-jaga user dihapus
            $transaksi->user->email ?? '-',
            Str::title(str_replace('_', ' ', $transaksi->metode_pembayaran)),
            $produkList,
            $totalQty,
            $transaksi->total_harga,
            $fullAlamat,
        ];
    }

    /**
     * Terapkan style
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}