<?php

namespace App\Http\Controllers;

// --- TAMBAHAN BARU ---
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\View\View;
// --- BATAS TAMBAHAN ---

class HomeController extends Controller
{
    /**
     * Tampilkan homepage publik dengan filter.
     */
    public function index(Request $request): View
    {
        // Ambil query builder untuk produk
        // Kita eager load relasi 'toko' dan 'toko.provinsi'
        $query = Produk::with(['toko.provinsi', 'kategori'])
                       ->where('stok', '>', 0); // Hanya tampilkan produk yang ada stok

        // --- Logika Filter ---

        // 1. Filter Pencarian (Nama Produk)
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // 2. Filter Kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // 3. Filter Provinsi
        if ($request->filled('provinsi_id')) {
            // Filter berdasarkan relasi: 'toko' punya 'provinsi_id'
            $query->whereHas('toko', function ($q) use ($request) {
                $q->where('provinsi_id', $request->provinsi_id);
            });
        }

        // --- Ambil Data ---

        // Ambil data produk yang sudah difilter, urutkan dari yang terbaru, 12 per halaman
        // 'paginate(12)' otomatis menangani halaman
        // 'withQueryString()' agar parameter filter (search, kategori, dll) tetap ada di link pagination
        $produks = $query->latest()->paginate(12)->withQueryString();

        // Ambil data untuk dropdown filter
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        $provinsis = Provinsi::orderBy('nama_provinsi', 'asc')->get();

        // Kirim semua data ke view
        return view('home', [
            'produks' => $produks,
            'kategoris' => $kategoris,
            'provinsis' => $provinsis,
        ]);
    }
}

