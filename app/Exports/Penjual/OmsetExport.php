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
// --- INI DIA PERBAIKANNYA ---
use Illuminate\Support\Str; // 1. Tambahkan use statement untuk Str
// --- BATAS PERBAIKAN ---

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
        // Query data berdasarkan filter yang sama di OmsetController
        $query = Transaksi::where('toko_id', $this->tokoId)
                        // --- INI PERBAIKANNYA ---
                        ->where('status_transaksi', 'selesai') // 2. Perbaiki kolom (sudah benar)
                        // --- BATAS PERBAIKAN ---
                        ->with('user', 'detailTransaksis.produk'); // Ambil relasi

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
        // Definisikan judul kolom di file Excel
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
        // Ubah format data per baris
        
        // Gabungkan detail produk menjadi satu string
        $produkList = $transaksi->detailTransaksis->map(function ($detail) {
            return $detail->jumlah . 'x ' . $detail->produk->nama_produk;
        })->implode("\n"); // Pisahkan dengan baris baru

        $totalQty = $transaksi->detailTransaksis->sum('jumlah');

        $alamat = $transaksi->alamat_pengiriman;
        $fullAlamat = sprintf(
            "%s, %s, %s, %s, %s",
            $alamat['alamat_lengkap'],
            $alamat['kecamatan'],
            $alamat['kota_kabupaten'],
            $alamat['provinsi'],
            $alamat['kode_pos']
        );

        return [
            $transaksi->invoice_id,
            $transaksi->updated_at->format('Y-m-d H:i:s'),
            $transaksi->user->name,
            $transaksi->user->email,
            // --- INI DIA PERBAIKANNYA ---
            Str::title(str_replace('_', ' ', $transaksi->metode_pembayaran)), // 3. Panggil Str
            // --- BATAS PERBAIKAN ---
            $produkList,
            $totalQty,
            $transaksi->total_harga, // Omset (sebelum ongkir)
            $fullAlamat,
        ];
    }

    /**
     * Terapkan style (cth: Bold header)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style baris pertama (header)
            1    => ['font' => ['bold' => true]],
        ];
    }
}