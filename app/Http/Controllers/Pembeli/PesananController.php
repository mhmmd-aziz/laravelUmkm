<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PesananController extends Controller
{
    /**
     * Menampilkan riwayat pesanan (daftar transaksi).
     */
    public function index(): View
    {
        $transaksis = Transaksi::where('user_id', Auth::id())
                                ->with('toko', 'details.produk') // Eager load relasi
                                ->latest() // Urutkan dari yang terbaru
                                ->paginate(10);

        return view('pembeli.pesanan.index', compact('transaksis'));
    }

    /**
     * Menampilkan detail satu pesanan (transaksi).
     * (Kita buat view-nya nanti, siapkan fungsinya dulu)
     */
    public function show(Transaksi $transaksi): View
    {
        // Otorisasi: Pastikan user hanya bisa melihat pesanannya sendiri
        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Load relasi yang dibutuhkan
        $transaksi->load('toko', 'details.produk');

        // Nanti kita buat view 'pembeli.pesanan.show'
        // Untuk sekarang, kita bisa 'reuse' view index, tapi ini tidak ideal.
        // Sebaiknya kita redirect kembali saja.
        
        // return view('pembeli.pesanan.show', compact('transaksi'));
        
        // TODO: Buat view 'pembeli.pesanan.show.blade.php'
        // Untuk sementara, kita redirect ke index
        return view('pembeli.pesanan.index', [
            'transaksis' => Transaksi::where('id', $transaksi->id)->with('toko', 'details.produk')->paginate(1)
        ])->with('info', 'Halaman detail pesanan sedang dalam pengembangan. Ini adalah detail untuk ' . $transaksi->invoice_id);
    }
}
