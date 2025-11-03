<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User; // Nanti kita perlukan

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin.
     */
    public function index(): View
    {
        // Rute ini sudah diatur untuk me-return view 'admin.dashboard'
        // Nanti kita bisa tambahkan data di sini
        
        // $jumlah_user = User::count();
        // $jumlah_toko = Toko::count();
        // $total_penjualan = Transaksi::sum('total_harga');

        return view('admin.dashboard'/*, compact('jumlah_user', 'jumlah_toko', 'total_penjualan')*/);
    }
}

