<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\Penjual\OmsetExport; // Panggil class Export
use Maatwebsite\Excel\Facades\Excel; // Panggil facade Excel
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OmsetController extends Controller
{
    /**
     * Menampilkan halaman laporan omset.
     */
    public function index(Request $request): View
    {
        // Validasi input filter (opsional tapi bagus)
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $toko_id = Auth::user()->toko->id;
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_selesai = $request->input('tanggal_selesai');

        // --- 1. Query untuk Total Box ---
        // PENTING: Omset HANYA dihitung dari status 'selesai'
        
        // Omset Hari Ini
        $omsetHariIni = Transaksi::where('toko_id', $toko_id)
            ->where('status_pesanan', 'selesai')
            ->whereDate('updated_at', Carbon::today()) // Asumsi 'selesai' = tgl update
            ->sum('total_harga');

        // Omset 7 Hari Terakhir
        $omsetMingguIni = Transaksi::where('toko_id', $toko_id)
            ->where('status_pesanan', 'selesai')
            ->whereBetween('updated_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->sum('total_harga');

        // Omset Bulan Ini
        $omsetBulanIni = Transaksi::where('toko_id', $toko_id)
            ->where('status_pesanan', 'selesai')
            ->whereYear('updated_at', Carbon::now()->year)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->sum('total_harga');

        // Omset Tahun Ini
        $omsetTahunIni = Transaksi::where('toko_id', $toko_id)
            ->where('status_pesanan', 'selesai')
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('total_harga');

        // --- 2. Query untuk Grafik (12 Bulan Terakhir) ---
        $dataGrafik = $this->getDataGrafik($toko_id);

        // --- 3. Query untuk Tabel Hasil Filter (dengan Pagination) ---
        $queryFilter = Transaksi::where('toko_id', $toko_id)
            ->where('status_pesanan', 'selesai')
            ->with('user')
            ->latest('updated_at');

        if ($tanggal_mulai) {
            $queryFilter->where('updated_at', '>=', Carbon::parse($tanggal_mulai)->startOfDay());
        }
        if ($tanggal_selesai) {
            $queryFilter->where('updated_at', '<=', Carbon::parse($tanggal_selesai)->endOfDay());
        }

        $transaksisHasilFilter = $queryFilter->paginate(10);

        return view('penjual.omset.index', compact(
            'omsetHariIni',
            'omsetMingguIni',
            'omsetBulanIni',
            'omsetTahunIni',
            'dataGrafik',
            'transaksisHasilFilter'
        ));
    }

    /**
     * Menyiapkan data untuk grafik omset 12 bulan.
     */
    private function getDataGrafik($toko_id): array
    {
        $data = Transaksi::where('toko_id', $toko_id)
            ->where('status_pesanan', 'selesai')
            ->where('updated_at', '>=', Carbon::now()->subMonths(12)->startOfMonth())
            ->select(
                DB::raw('YEAR(updated_at) as tahun'),
                DB::raw('MONTH(updated_at) as bulan'),
                DB::raw('SUM(total_harga) as total_omset')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        // Siapkan array 12 bulan
        $labels = [];
        $dataPoints = [];
        $now = Carbon::now();

        for ($i = 11; $i >= 0; $i--) {
            $tanggal = $now->copy()->subMonths($i);
            $labels[] = $tanggal->format('M Y'); // Cth: Nov 2025
            
            $bulan = $tanggal->month;
            $tahun = $tanggal->year;

            // Cari data omset untuk bulan & tahun ini
            $omsetBulan = $data->first(function ($item) use ($bulan, $tahun) {
                return $item->bulan == $bulan && $item->tahun == $tahun;
            });

            $dataPoints[] = $omsetBulan ? $omsetBulan->total_omset : 0;
        }

        return [
            'labels' => $labels,
            'data' => $dataPoints,
        ];
    }

    /**
     * Memproses ekspor data omset ke Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        $toko_id = Auth::user()->toko->id;
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_selesai = $request->input('tanggal_selesai');

        // Buat nama file yang dinamis
        $fileName = 'Laporan_Omset_' . $toko_id . '_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        // Panggil class Export
        return Excel::download(
            new OmsetExport($toko_id, $tanggal_mulai, $tanggal_selesai), 
            $fileName
        );
    }
}
