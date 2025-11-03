<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Toko; // Import model Toko
use App\Models\Provinsi; // Import model Provinsi
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan ID user
use Illuminate\Support\Str; // Import Str untuk membuat slug

class TokoController extends Controller
{
    /**
     * Menampilkan form untuk membuat toko baru.
     */
    public function create(): View
    {
        // Ambil semua data provinsi dari database
        // Urutkan berdasarkan nama
        $provinsis = Provinsi::orderBy('nama_provinsi', 'asc')->get();
        
        // Kirim data provinsi ke view 'penjual.create_toko'
        return view('penjual.create_toko', [
            'provinsis' => $provinsis
        ]);
    }

    /**
     * Menyimpan toko baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi data
        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:255', 'unique:tokos,nama_toko'],
            'provinsi_id' => ['required', 'integer', 'exists:provinsis,id'], // Pastikan provinsi_id ada di tabel provinsis
            'alamat_toko' => ['required', 'string', 'max:1000'],
            'nomor_telepon' => ['required', 'string', 'max:20', 'regex:/^08[0-9]{8,12}$/'], // Validasi nomor HP Indonesia
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            // TODO: Tambahkan validasi upload logo (e.g., 'logo' => 'nullable|image|mimes:jpg,png|max:2048')
        ], [
            // 2. Custom error message (Bahasa Indonesia)
            'nama_toko.required' => 'Nama toko wajib diisi.',
            'nama_toko.unique' => 'Nama toko ini sudah terdaftar. Silakan pilih nama lain.',
            'provinsi_id.required' => 'Silakan pilih provinsi lokasi toko Anda.',
            'provinsi_id.exists' => 'Provinsi yang Anda pilih tidak valid.',
            'alamat_toko.required' => 'Alamat lengkap toko wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'nomor_telepon.regex' => 'Format nomor telepon tidak valid. Contoh: 081234567890.',
        ]);

        // 3. Proses simpan data
        try {
            // Nanti di sini kita tambahkan logika upload file logo
            // if ($request->hasFile('logo')) {
            //    $path_logo = $request->file('logo')->store('logos', 'public');
            // }

            Toko::create([
                'user_id' => Auth::id(), // ID penjual yang sedang login
                'nama_toko' => $validated['nama_toko'],
                'slug' => Str::slug($validated['nama_toko']), // Buat slug (e.g., "Toko Jaya" -> "toko-jaya")
                'provinsi_id' => $validated['provinsi_id'],
                'alamat_toko' => $validated['alamat_toko'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'deskripsi' => $validated['deskripsi'],
                'is_active' => true, // Langsung aktifkan tokonya
                // 'logo_url' => $path_logo ?? null, // Nanti diisi setelah ada upload
            ]);

        } catch (\Exception $e) {
            // Jika gagal (misal slug tidak unik atau error database), kembalikan dengan error
            // Tampilkan pesan error yang lebih deskriptif
            \Log::error('Gagal membuat toko: ' . $e->getMessage()); // Catat error di log
            return back()->with('error', 'Terjadi kesalahan saat membuat toko. Silakan coba lagi.')->withInput();
        }

        // 4. Jika berhasil, arahkan ke dashboard penjual
        return redirect()->route('penjual.dashboard')->with('success', 'Toko Anda berhasil dibuat!');
    }
}

