<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\Penjual\OmsetExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OmsetController extends Controller
{
    /**
     * Menampilkan halaman laporan omset.
     */
    public function index(Request $request): View
    {
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $toko_id = Auth::user()->toko->id;
        
        // --- INI DIA PERBAIKANNYA (Ambil tanggal filter) ---
        // Jika tidak ada filter, default 30 hari terakhir
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->subDays(29)->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::now()->toDateString());
        // --- BATAS PERBAIKAN ---

        // Query dasar untuk omset
        $queryOmset = Transaksi::where('toko_id', $toko_id)
                         ->where('status_transaksi', 'selesai');

        // --- 1. Query untuk Total Box ---
        $omsetHariIni = (clone $queryOmset)
            ->whereDate('updated_at', Carbon::today())
            ->sum('total_harga');

        $omsetMingguIni = (clone $queryOmset)
            ->whereBetween('updated_at', [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()->endOfDay()])
            ->sum('total_harga');

        $omsetBulanIni = (clone $queryOmset)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('total_harga');

        $omsetTahunIni = (clone $queryOmset)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('total_harga');

        // --- 2. Query untuk Grafik ---
        // --- INI DIA PERBAIKANNYA (Kirim filter ke fungsi grafik) ---
        $dataGrafik = $this->getDataGrafikHarian($toko_id, $tanggalMulai, $tanggalSelesai);
        // --- BATAS PERBAIKAN ---

        // --- 3. Query untuk Tabel Hasil Filter (dengan Pagination) ---
        $queryFilter = (clone $queryOmset)
            ->with('user')
            ->where('updated_at', '>=', Carbon::parse($tanggalMulai)->startOfDay())
            ->where('updated_at', '<=', Carbon::parse($tanggalSelesai)->endOfDay())
            ->latest('updated_at');

        $transaksiSelesai = $queryFilter->paginate(10)->appends($request->query());

        // Ubah data grafik ke JSON untuk dilempar ke View
        $dataGrafikJson = json_encode($dataGrafik);

        return view('penjual.omset.index', compact(
            'omsetHariIni',
            'omsetMingguIni',
            'omsetBulanIni',
            'omsetTahunIni',
            'dataGrafikJson', // Kirim data JSON
            'transaksiSelesai',
            'tanggalMulai', // Kirim balik tanggal filter untuk diisi di form
            'tanggalSelesai'
        ));
    }

    /**
     * Menyiapkan data untuk grafik omset harian berdasarkan rentang.
     */
    // --- INI DIA PERBAIKANNYA (Terima parameter filter) ---
    private function getDataGrafikHarian($toko_id, $tanggalMulai, $tanggalSelesai): array
    {
        // Ambil data omset per HARI
        $data = Transaksi::where('toko_id', $toko_id)
            ->where('status_transaksi', 'selesai')
            ->whereBetween('updated_at', [Carbon::parse($tanggalMulai)->startOfDay(), Carbon::parse($tanggalSelesai)->endOfDay()])
            ->select(
                DB::raw('DATE(updated_at) as tanggal'),
                DB::raw('SUM(total_harga) as total_omset')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->keyBy('tanggal'); // Jadikan tanggal sebagai key

        // Siapkan array untuk semua tanggal dalam rentang (termasuk yang 0)
        $labels = [];
        $dataPoints = [];
        
        // Buat periode tanggal dari mulai sampai selesai
        $period = Carbon::parse($tanggalMulai)->daysUntil(Carbon::parse($tanggalSelesai));

        foreach ($period as $date) {
            $formatTanggal = $date->format('Y-m-d');
            $labels[] = $date->format('d M'); // Label (Cth: 08 Nov)
            
            // Cek jika ada data omset di tanggal ini
            $dataPoints[] = $data->get($formatTanggal)->total_omset ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $dataPoints,
        ];
    }
    // --- BATAS PERBAIKAN ---

    /**
     * Memproses ekspor data omset ke Excel.
     * (Fungsi ini sudah benar dari langkah sebelumnya)
     */
    public function export(Request $request): BinaryFileResponse
    {
        $toko_id = Auth::user()->toko->id;
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_selesai = $request->input('tanggal_selesai');

        $fileName = 'Laporan_Omset_' . $toko_id . '_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new OmsetExport($toko_id, $tanggal_mulai, $tanggal_selesai), 
            $fileName
        );
    }
}