<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// --- TAMBAHAN BARU ---
use App\Models\Produk;
use Illuminate\View\View;
// --- BATAS TAMBAHAN ---

class ProdukPublikController extends Controller
{
    /**
     * Menampilkan halaman detail satu produk.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\View\View
     */
    public function show(Produk $produk): View
    {
        // Berkat Route-Model Binding & getRouteKeyName() di Model,
        // Laravel otomatis mencari produk berdasarkan SLUG.
        
        // Kita juga bisa load relasi yang dibutuhkan
        $produk->load(['toko.provinsi', 'kategori']);

        // Tampilkan view
        return view('produk.show', compact('produk'));
    }
}
