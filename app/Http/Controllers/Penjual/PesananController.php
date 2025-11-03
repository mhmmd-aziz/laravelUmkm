<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class PesananController extends Controller
{
    /**
     * Menampilkan daftar pesanan yang masuk ke toko penjual.
     */
    public function index(Request $request): View
    {
        // 1. Ambil toko milik penjual yang sedang login
        $toko = Auth::user()->toko;

        // 2. Query transaksi HANYA untuk toko ini
        $query = Transaksi::where('toko_id', $toko->id)
                          ->with('user') // Ambil data pembeli
                          ->latest(); // Urutkan dari yang terbaru

        // TODO: Tambahkan filter berdasarkan status
        
        $transaksis = $query->paginate(15);

        return view('penjual.pesanan.index', compact('transaksis'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show(Transaksi $transaksi): View|RedirectResponse
    {
        // 1. Otorisasi: Pastikan transaksi ini milik toko penjual
        if ($transaksi->toko_id !== Auth::user()->toko->id) {
            \Log::warning("Aksi tidak diizinkan: User " . Auth::id() . " mencoba akses transaksi " . $transaksi->id . " milik toko " . $transaksi->toko_id);
            abort(403, 'Aksi tidak diizinkan.');
        }

        // 2. Load relasi yang dibutuhkan
        $transaksi->load('user', 'details.produk');

        // 3. Tampilkan view
        return view('penjual.pesanan.show', compact('transaksi'));
    }

    /**
     * Update status pesanan (misal: dari dikemas -> dikirim).
     */
    public function updateStatus(Request $request, Transaksi $transaksi): RedirectResponse
    {
        // 1. Otorisasi: Pastikan transaksi ini milik toko penjual
        if ($transaksi->toko_id !== Auth::user()->toko->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // 2. Validasi status baru
        $validated = $request->validate([
            'status_pesanan' => [
                'required', 
                'string', 
                Rule::in(['menunggu_pembayaran', 'dikemas', 'dikirim', 'selesai', 'dibatalkan'])
            ],
            // TODO: Tambahkan validasi 'nomor_resi' jika status == 'dikirim'
        ]);

        // 3. Update status
        try {
            $transaksi->status_pesanan = $validated['status_pesanan'];
            
            // TODO: Simpan nomor resi jika ada
            
            $transaksi->save();

        } catch (\Exception $e) {
            \Log::error('Gagal update status pesanan: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui status pesanan.');
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
