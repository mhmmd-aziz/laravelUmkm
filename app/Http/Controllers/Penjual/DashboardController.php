<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
// Import Model yang dibutuhkan
use App\Models\Transaksi; 
use App\Models\Produk;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard penjual.
     */
    public function index(): View
    {
        // 1. Ambil Data Toko User
        $user = Auth::user();
        
        // Cek safety jika user akses dashboard tapi belum buat toko
        if (!$user->toko) {
            return redirect()->route('penjual.toko.create'); 
        }

        $toko = $user->toko;
        $toko_id = $toko->id;

        // 2. Hitung Data Statistik (Cards)
        
        // Total Omset (Hanya status 'selesai')
        $totalOmset = Transaksi::where('toko_id', $toko_id)
                        ->where('status_transaksi', 'selesai')
                        ->sum('total_harga');

        // Total Pesanan (Semua transaksi yang masuk)
        $totalPesanan = Transaksi::where('toko_id', $toko_id)->count();

        // Total Produk
        $totalProduk = Produk::where('toko_id', $toko_id)->count();

        // Pesanan Pending (Perlu diproses/dikemas)
        $pesananPending = Transaksi::where('toko_id', $toko_id)
                            ->whereIn('status_transaksi', ['menunggu_pembayaran', 'diproses', 'dikemas'])
                            ->count();

        // 3. Ambil 5 Transaksi Terbaru untuk Tabel
        $recentOrders = Transaksi::with('user') // Eager load user pembeli
                            ->where('toko_id', $toko_id)
                            ->latest() // Urutkan dari yang terbaru
                            ->take(5)
                            ->get();

        // 4. Data untuk Grafik (7 Hari Terakhir)
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d M'); // Format: 22 Nov
            
            // Hitung omset per hari
            $dailyOmset = Transaksi::where('toko_id', $toko_id)
                            ->where('status_transaksi', 'selesai')
                            ->whereDate('updated_at', $date) // Gunakan updated_at untuk transaksi selesai
                            ->sum('total_harga');
            
            $chartData[] = $dailyOmset;
        }

        // 5. Kirim semua variabel ke View
        return view('penjual.dashboard', compact(
            'toko',
            'totalOmset',
            'totalPesanan',
            'totalProduk',
            'pesananPending',
            'recentOrders',
            'chartLabels',
            'chartData'
        ));
    }
}