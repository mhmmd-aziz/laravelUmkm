<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// --- INI DIA PERBAIKANNYA ---
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. Tambahkan use statement

class ProdukController extends Controller
{
    // --- INI DIA PERBAIKANNYA ---
    use AuthorizesRequests; // 2. Tambahkan use trait

    /**
     * Menampilkan daftar produk milik penjual.
     */
    public function index(): View
    {
        $toko = Auth::user()->toko;
        $produks = Produk::where('toko_id', $toko->id)
                         ->with('kategori') 
                         ->latest()
                         ->paginate(10); 

        return view('penjual.produk.index', compact('produks'));
    }

    /**
     * Menampilkan form untuk membuat produk baru.
     */
    public function create(): View
    {
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('penjual.produk.create', compact('kategoris'));
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategoris,id'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'deskripsi_singkat' => ['required', 'string', 'max:500'],
            'deskripsi_lengkap' => ['nullable', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'berat_gram' => ['required', 'integer', 'min:1'],
            'gambar_produk_utama' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $toko_id = Auth::user()->toko->id;

        $gambarPath = null;
        if ($request->hasFile('gambar_produk_utama')) {
            $gambarPath = $request->file('gambar_produk_utama')->store('produks', 'public');
        }

        try {
            Produk::create([
                'toko_id' => $toko_id,
                'kategori_id' => $validated['kategori_id'],
                'nama_produk' => $validated['nama_produk'],
                'slug' => Str::slug($validated['nama_produk']) . '-' . uniqid(), 
                'deskripsi_singkat' => $validated['deskripsi_singkat'],
                'deskripsi_lengkap' => $validated['deskripsi_lengkap'],
                'harga' => $validated['harga'],
                'stok' => $validated['stok'],
                'berat_gram' => $validated['berat_gram'],
                'gambar_produk_utama' => $gambarPath,
            ]);
        } catch (\Exception $e) {
            if ($gambarPath) {
                Storage::disk('public')->delete($gambarPath);
            }
            \Log::error('Gagal menyimpan produk: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan produk. Silakan coba lagi.')->withInput();
        }

        return redirect()->route('penjual.produk.index')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit produk.
     */
    public function edit(Produk $produk): View
    {
        // Otorisasi: Pastikan produk ini milik toko user yang sedang login
        $this->authorize('update', $produk); // <-- Ini yang menyebabkan error

        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('penjual.produk.edit', compact('produk', 'kategoris'));
    }

    /**
     * Memperbarui produk di database.
     */
    public function update(Request $request, Produk $produk): RedirectResponse
    {
        // Otorisasi: Pastikan produk ini milik toko user yang sedang login
        $this->authorize('update', $produk); // <-- Ini yang menyebabkan error

        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', 'exists:kategoris,id'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'deskripsi_singkat' => ['required', 'string', 'max:500'],
            'deskripsi_lengkap' => ['nullable', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'berat_gram' => ['required', 'integer', 'min:1'],
            'gambar_produk_utama' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $dataToUpdate = $validated;

        if ($request->hasFile('gambar_produk_utama')) {
            if ($produk->gambar_produk_utama) {
                Storage::disk('public')->delete($produk->gambar_produk_utama);
            }
            $gambarPath = $request->file('gambar_produk_utama')->store('produks', 'public');
            $dataToUpdate['gambar_produk_utama'] = $gambarPath;
        }

        if ($request->nama_produk !== $produk->nama_produk) {
            $dataToUpdate['slug'] = Str::slug($request->nama_produk) . '-' . uniqid();
        }

        try {
            $produk->update($dataToUpdate);
        } catch (\Exception $e) {
            \Log::error('Gagal memperbarui produk: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui produk. Silakan coba lagi.')->withInput();
        }

        return redirect()->route('penjual.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Produk $produk): RedirectResponse
    {
        // 1. Otorisasi
        $this->authorize('delete', $produk);

        try {
            // 2. Hapus data (Otomatis Soft Delete karena model sudah pakai trait SoftDeletes)
            // Laravel hanya akan mengisi kolom 'deleted_at' dengan waktu sekarang
            // Data TIDAK hilang dari database, tapi disembunyikan dari query biasa.
            $produk->delete();

            // CATATAN PENTING:
            // Kita JANGAN menghapus gambar dari Storage::disk('public')
            // Agar jika dilihat di riwayat pesanan lama, gambarnya masih muncul.

        } catch (\Exception $e) {
            \Log::error('Gagal menghapus produk: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus produk.');
        }

        return redirect()->route('penjual.produk.index')->with('success', 'Produk berhasil dihapus (diarsipkan).');
    }
}

