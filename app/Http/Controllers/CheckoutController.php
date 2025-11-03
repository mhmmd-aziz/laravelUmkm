<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Alamat;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
// --- TAMBAHAN BARU ---
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB; // Untuk Database Transaction
use Carbon\Carbon; // Untuk timestamp
// --- BATAS TAMBAHAN ---


class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     * (Fungsi ini sudah benar dari Langkah 9, tidak perlu diubah)
     */
    public function index(): View|RedirectResponse
    {
        if (Cart::isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong. Silakan belanja dulu.');
        }

        $user = Auth::user();
        $alamats = Alamat::where('user_id', $user->id)->orderBy('label_alamat', 'asc')->get();
        $cartItems = Cart::getContent()->sortBy('name');
        $total = Cart::getTotal();

        if ($alamats->isEmpty()) {
            return redirect()->route('profile.edit')->with('error', 'Anda harus menambahkan alamat pengiriman terlebih dahulu sebelum checkout.');
        }

        return view('checkout.index', compact('alamats', 'cartItems', 'total'));
    }

    /**
     * Memproses dan menyimpan pesanan.
     * (INI ADALAH FUNGSI YANG KITA ISI)
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi input
        $request->validate([
            'alamat_id' => ['required', 'integer', 'exists:alamats,id'],
            'metode_pembayaran' => ['required', 'string', Rule::in(['bank_transfer'])],
        ]);

        // 2. Otorisasi alamat
        $alamat = Alamat::find($request->alamat_id);
        if ($alamat->user_id !== Auth::id()) {
            return back()->with('error', 'Alamat tidak valid.');
        }
        // Simpan alamat sebagai JSON (snapshot)
        $alamatJson = $alamat->toJson();

        // 3. Cek keranjang
        if (Cart::isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $cartItems = Cart::getContent();
        $user = Auth::user();

        // 4. Pisahkan item keranjang berdasarkan Toko (toko_id)
        $itemsPerToko = $cartItems->groupBy('attributes.toko_id');
        
        $transaksiBaruDibuat = [];

        // 5. Mulai Database Transaction (SANGAT PENTING)
        // Ini memastikan jika ada 1 saja error (misal stok habis),
        // semua data yang sudah telanjur dibuat akan dibatalkan (rollback).
        try {
            DB::beginTransaction();

            // Loop untuk setiap T OKO
            foreach ($itemsPerToko as $toko_id => $items) {
                
                // 6. Hitung total harga HANYA untuk toko ini
                $totalHargaPerToko = $items->sum(fn($item) => $item->getPriceSum());

                // 7. Buat 1 Transaksi baru untuk toko ini
                $transaksi = Transaksi::create([
                    'user_id' => $user->id,
                    'toko_id' => $toko_id,
                    'invoice_id' => 'INV-' . $toko_id . '-' . Carbon::now()->timestamp, // Invoice ID Unik
                    'total_harga' => $totalHargaPerToko,
                    'status_pesanan' => 'menunggu_pembayaran', // Status awal
                    'alamat_pengiriman_json' => $alamatJson,
                    'metode_pembayaran' => $request->metode_pembayaran,
                ]);

                // Loop untuk setiap ITEM di dalam toko ini
                foreach ($items as $item) {
                    // 8. Ambil produk dan KUNCI (lock) row-nya untuk update stok
                    // Ini mencegah 'race condition' jika ada 2 orang beli barang yang sama
                    $produk = Produk::lockForUpdate()->find($item->id);

                    // 9. Cek stok (lagi, untuk keamanan)
                    if (!$produk || $produk->stok < $item->quantity) {
                        // Jika gagal, batalkan semua
                        throw new \Exception('Stok produk ' . $item->name . ' tidak mencukupi.');
                    }

                    // 10. Buat Detail Transaksi
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $produk->id,
                        'jumlah' => $item->quantity,
                        'harga_satuan' => $item->price,
                    ]);

                    // 11. Kurangi Stok Produk
                    $produk->stok -= $item->quantity;
                    $produk->save();
                }

                // Simpan transaksi yang baru dibuat untuk halaman sukses
                $transaksiBaruDibuat[] = $transaksi->load('toko'); // Load relasi toko
            }

            // 12. Jika semua loop berhasil, commit transaction
            DB::commit();

        } catch (\Exception $e) {
            // 13. Jika ada error di mana saja, batalkan semua (rollback)
            DB::rollBack();
            \Log::error('Gagal membuat transaksi: ' . $e->getMessage());
            // Kembalikan ke keranjang dengan pesan error
            return redirect()->route('cart.index')->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }

        // 14. Jika semua berhasil, kosongkan keranjang
        Cart::clear();

        // 15. Redirect ke halaman "Pesanan Berhasil"
        // Kita kirim data transaksi baru via Session Flash
        return redirect()->route('checkout.success')->with('transaksis', $transaksiBaruDibuat);
    }

    /**
     * Menampilkan halaman pesanan berhasil.
     */
    public function success(Request $request): View|RedirectResponse
    {
        // Ambil data transaksi dari session
        $transaksis = $request->session()->get('transaksis');

        // Jika tidak ada data (misal user refresh halaman), redirect ke riwayat pesanan
        if (!$transaksis) {
            return redirect()->route('pembeli.pesanan.index');
        }

        return view('checkout.success');
    }
}

