<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
// --- TAMBAHAN BARU ---
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
// --- BATAS TAMBAHAN ---

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan statistik platform.
     */
    public function index(): View
    {
        // 1. K-Stat (Kartu Statistik)
        $totalUsers = User::count();
        $totalPembeli = User::where('role', 'pembeli')->count();
        $totalPenjual = User::where('role', 'penjual')->count();
        $totalProduk = Produk::count();
        
        // Total Omset (hanya dari transaksi 'selesai')
        $totalOmset = Transaksi::where('status_transaksi', 'selesai')->sum('total_bayar');
        // Total Transaksi (semua status)
        $totalTransaksi = Transaksi::count();

        // 2. Data Grafik (Omset 30 Hari Terakhir)
        $dataGrafik = $this->getDataGrafikHarian();
        $dataGrafikJson = json_encode($dataGrafik);

        // 3. Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPembeli',
            'totalPenjual',
            'totalProduk',
            'totalOmset',
            'totalTransaksi',
            'dataGrafikJson'
        ));
    }

    /**
     * Menyiapkan data untuk grafik omset 30 hari terakhir (seluruh platform).
     */
    private function getDataGrafikHarian(): array
    {
        // Ambil data omset per HARI (hanya yang 'selesai')
        $data = Transaksi::where('status_transaksi', 'selesai')
            ->whereBetween('updated_at', [Carbon::now()->subDays(29)->startOfDay(), Carbon::now()->endOfDay()])
            ->select(
                DB::raw('DATE(updated_at) as tanggal'),
                DB::raw('SUM(total_bayar) as total_omset') // Gunakan total_bayar untuk omset platform
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->keyBy('tanggal'); // Jadikan tanggal sebagai key

        // Siapkan array untuk semua 30 tanggal (termasuk yang 0)
        $labels = [];
        $dataPoints = [];
        
        $period = Carbon::now()->subDays(29)->daysUntil(Carbon::now());

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
}