<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Darryldecode\Cart\Facades\CartFacade as Cart; // Import Facade
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index(): View
    {
        $cartItems = Cart::getContent()->sortBy('name');
        return view('cart.index', compact('cartItems'));
    }

    /**
     * Menambahkan item ke keranjang.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi (Admin/Penjual tidak bisa beli)
        if (Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'penjual')) {
            return back()->with('error', 'Akun Admin/Penjual tidak dapat melakukan pembelian.');
        }

        // 2. Validasi input
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'produk_slug' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        // 3. Cari produk di database
        $produk = Produk::with('toko')->find($request->produk_id);

        if (!$produk) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        // 4. Cek Stok
        if ($produk->stok < $request->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi (tersisa: ' . $produk->stok . ').');
        }

        // 5. Cek apakah produk dari toko sendiri (jika pembeli juga penjual? - skenario kompleks)
        // Untuk saat ini, kita anggap role 'pembeli' murni.

        // 6. Logika Tambah ke Keranjang
        try {
            Cart::add([
                'id' => $produk->id,
                'name' => $produk->nama_produk,
                'price' => $produk->harga,
                'quantity' => $request->quantity,
                'attributes' => [
                    'image' => $produk->gambar_produk_utama,
                    'slug' => $produk->slug,
                    'berat_gram' => $produk->berat_gram,
                    'stok_maks' => $produk->stok,
                    'toko_id' => $produk->toko->id,
                    'toko_nama' => $produk->toko->nama_toko,
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Gagal tambah ke keranjang: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan produk ke keranjang. Silakan coba lagi.');
        }

        // 7. Redirect kembali ke halaman produk dengan pesan sukses
        return redirect()->route('produk.show', $request->produk_slug)
                         ->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update kuantitas item di keranjang.
     */
    public function update(Request $request, $itemId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Cart::get($itemId);
        
        // Cek stok maks
        if ($request->quantity > $item->attributes->stok_maks) {
            return back()->with('error', 'Stok produk tidak mencukupi (tersisa: ' . $item->attributes->stok_maks . ').');
        }

        Cart::update($itemId, [
            'quantity' => [
                'relative' => false, // Set kuantitas baru, bukan menambah/mengurangi
                'value' => $request->quantity
            ],
        ]);

        return back()->with('success', 'Kuantitas produk berhasil diperbarui.');
    }

    /**
     * Hapus item dari keranjang.
     */
    public function destroy($itemId): RedirectResponse
    {
        Cart::remove($itemId);
        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Hapus semua item dari keranjang.
     */
    public function clear(): RedirectResponse
    {
        Cart::clear();
        return back()->with('success', 'Keranjang belanja berhasil dikosongkan.');
    }
}
