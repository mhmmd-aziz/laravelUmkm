<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule; // <-- Import Rule

class PesananController extends Controller
{
    /**
     * Menampilkan daftar pesanan yang masuk ke toko penjual.
     */
    public function index(): View
    {
        $toko_id = Auth::user()->toko->id;

        // Ambil semua transaksi yang terkait dengan toko ini
        // Urutkan berdasarkan yang terbaru
        $transaksis = Transaksi::where('toko_id', $toko_id)
                            ->with('user') // Ambil data pembeli
                            ->latest()
                            ->paginate(15);

        return view('penjual.pesanan.index', compact('transaksis'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show(Transaksi $transaksi): View
    {
        // Otorisasi: Pastikan penjual ini berhak melihat transaksi ini
        // (Transaksi ini harus milik tokonya)
        if ($transaksi->toko_id !== Auth::user()->toko->id) {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }

        // Load relasi detail_transaksis dan produk di dalamnya
        $transaksi->load('detailTransaksis.produk');

        $transaksi->alamat_pengiriman = json_decode($transaksi->alamat_pengiriman, true);

        return view('penjual.pesanan.show', compact('transaksi'));
    }

    /**
     * Update status pesanan (dikemas, dikirim, selesai, dibatalkan).
     */
    public function updateStatus(Request $request, Transaksi $transaksi): RedirectResponse
    {
        // Otorisasi: Pastikan penjual ini berhak mengupdate transaksi ini
        if ($transaksi->toko_id !== Auth::user()->toko->id) {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }

        // --- INI PERBAIKANNYA ---
        // Validasi nama kolom yang benar 'status_transaksi'
        $validated = $request->validate([
            'status_transaksi' => [
                'required',
                Rule::in(['menunggu_pembayaran', 'diproses', 'dikemas', 'dikirim', 'selesai', 'dibatalkan']),
            ],
            'nomor_resi' => 'nullable|string|max:255', // Tambahkan validasi resi
        ]);

        try {
            // Update status
            $transaksi->status_transaksi = $validated['status_transaksi'];

            // Jika status 'dikirim', simpan nomor resi
            if ($validated['status_transaksi'] === 'dikirim' && !empty($validated['nomor_resi'])) {
                // Asumsi kita simpan resi di 'catatan_penjual' atau buat kolom baru
                // Untuk sekarang, kita simpan di catatan_penjual
                $transaksi->catatan_penjual = 'Resi: ' . $validated['nomor_resi'];
            }
            
            // Jika status 'selesai', tandai tanggal selesai
            if ($validated['status_transaksi'] === 'selesai') {
                $transaksi->updated_at = now(); // Ini akan digunakan untuk filter omset
            }

            $transaksi->save();

            return back()->with('success', 'Status pesanan berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Gagal update status pesanan: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui status pesanan. Silakan coba lagi.');
        }
    }
}