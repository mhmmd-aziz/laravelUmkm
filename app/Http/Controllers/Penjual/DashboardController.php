<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // Nanti kita perlukan
use App\Models\Toko; // Nanti kita perlukan
use App\Models\Transaksi; // Nanti kita perlukan

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard penjual.
     */
    public function index(): View
    {
        // Middleware 'toko.exists' sudah memastikan penjual punya toko
        // jadi kita bisa panggil Auth::user()->toko dengan aman.
        
        // $toko = Auth::user()->toko;
        // $omset_hari_ini = Transaksi::where('toko_id', $toko->id)
        //                            ->whereDate('created_at', today())
        //                            ->sum('total_harga');

        return view('penjual.dashboard'/*, compact('omset_hari_ini')*/);
    }
}

