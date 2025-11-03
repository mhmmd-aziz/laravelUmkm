<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AlamatController extends Controller
{
    // Kita tidak pakai index() atau show() karena ditampilkan di Profile

    /**
     * Menampilkan form untuk membuat alamat baru.
     */
    public function create(): View
    {
        return view('pembeli.alamat.create');
    }

    /**
     * Menyimpan alamat baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi data (mirip seperti di migrasi)
        $validated = $request->validate([
            'label_alamat' => ['required', 'string', 'max:255'],
            'nama_penerima' => ['required', 'string', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'alamat_lengkap' => ['required', 'string'],
            'provinsi' => ['required', 'string', 'max:255'],
            'kota_kabupaten' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'kode_pos' => ['required', 'string', 'max:10'],
        ]);

        // Tambahkan user_id ke data yang divalidasi
        $validated['user_id'] = Auth::id();

        try {
            Alamat::create($validated);
        } catch (\Exception $e) {
            \Log::error('Gagal simpan alamat: ' . $e->getMessage());
            return redirect()->route('profile.edit')->with('error', 'Gagal menyimpan alamat baru.');
        }

        return redirect()->route('profile.edit')->with('success', 'Alamat baru berhasil disimpan!');
    }


    /**
     * Menampilkan form untuk mengedit alamat.
     */
    public function edit(Alamat $alamat): View
    {
        // Otorisasi: Pastikan user hanya bisa mengedit alamatnya sendiri
        if ($alamat->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        return view('pembeli.alamat.edit', compact('alamat'));
    }

    /**
     * Memperbarui alamat di database.
     */
    public function update(Request $request, Alamat $alamat): RedirectResponse
    {
        // Otorisasi
        if ($alamat->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Validasi data
        $validated = $request->validate([
            'label_alamat' => ['required', 'string', 'max:255'],
            'nama_penerima' => ['required', 'string', 'max:255'],
            'nomor_telepon' => ['required', 'string', 'max:20'],
            'alamat_lengkap' => ['required', 'string'],
            'provinsi' => ['required', 'string', 'max:255'],
            'kota_kabupaten' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'kode_pos' => ['required', 'string', 'max:10'],
        ]);

        try {
            $alamat->update($validated);
        } catch (\Exception $e) {
            \Log::error('Gagal update alamat: ' . $e->getMessage());
            return redirect()->route('profile.edit')->with('error', 'Gagal memperbarui alamat.');
        }

        return redirect()->route('profile.edit')->with('success', 'Alamat berhasil diperbarui!');
    }

    /**
     * Menghapus alamat dari database.
     */
    public function destroy(Alamat $alamat): RedirectResponse
    {
        // Otorisasi
        if ($alamat->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        try {
            $alamat->delete();
        } catch (\Exception $e) {
            \Log::error('Gagal hapus alamat: ' . $e->getMessage());
            return redirect()->route('profile.edit')->with('error', 'Gagal menghapus alamat.');
        }

        return redirect()->route('profile.edit')->with('success', 'Alamat berhasil dihapus!');
    }
}
