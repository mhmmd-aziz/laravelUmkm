<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
// --- INI DIA PERBAIKANNYA ---
use App\Models\Transaksi; // <-- Pastikan ini di-use
// --- BATAS PERBAIKAN ---
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PesananController extends Controller
{
    /**
     * Menampilkan riwayat pesanan milik pembeli.
     */
    public function index(): View
    {
        $userId = Auth::id();

        // Ambil data transaksi milik user, urutkan dari terbaru
        // --- INI DIA PERBAIKANNYA (details -> detailTransaksis) ---
        $transaksis = Transaksi::where('user_id', $userId)
                            ->with(['toko', 'detailTransaksis.produk']) // Eager load relasi
                            ->latest() // Urutkan dari terbaru
                            ->paginate(10); // 10 pesanan per halaman
        // --- BATAS PERBAIKAN ---

        return view('pembeli.pesanan.index', compact('transaksis'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show(Transaksi $transaksi): View
    {
        // Otorisasi: Pastikan pembeli ini berhak melihat transaksi ini
        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }

        // Load relasi
        // --- INI DIA PERBAIKANNYA (details -> detailTransaksis) ---
        $transaksi->load(['toko', 'detailTransaksis.produk']);
        // --- BATAS PERBAIKAN ---

        // TODO: Buat view 'pembeli.pesanan.show'
        // Untuk saat ini, kita bisa 'reuse' view penjual jika tampilannya sama
        // atau buat view baru yang spesifik untuk pembeli.
        
        // Asumsi kita akan buat view baru nanti:
        // return view('pembeli.pesanan.show', compact('transaksi'));

        // Untuk sementara, kita kembalikan ke index (atau beri pesan)
        // Halaman detail untuk pembeli belum kita buat, tapi error relasi sudah diperbaiki.
        // Kita redirect saja ke index untuk sementara.
        // return redirect()->route('pembeli.pesanan.index')->with('info', 'Halaman detail pesanan pembeli belum dibuat.');
        
        // Mari kita buat view-nya sekalian di langkah berikutnya jika error ini sudah fix.
        // Untuk sekarang, kita fokus perbaiki error relasi.
        
        // Update: Mari kita gunakan view 'pembeli.pesanan.index' lagi saja,
        // tapi kita perlu buatkan view 'show' nya.
        
        // --- FOKUS PADA PERBAIKAN ERROR ---
        // Kode di atas ($transaksi->load(...)) sudah memperbaiki error relasi.
        // Tapi kita belum punya view 'pembeli.pesanan.show'.
        
        // Mari kita buat view 'show' untuk pembeli sekarang
        return view('pembeli.pesanan.show', compact('transaksi'));
    }
}