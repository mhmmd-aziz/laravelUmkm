<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // <-- SAYA LUPA BARIS INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class DashboardRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     * Mengarahkan pengguna ke dashboard yang sesuai berdasarkan role.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // 1. Jika role adalah ADMIN
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 2. Jika role adalah PENJUAL
        if ($user->role === 'penjual') {
            // Cek apakah penjual sudah punya toko
            if ($user->toko) {
                // Jika sudah punya, arahkan ke dashboard penjual
                return redirect()->route('penjual.dashboard');
            } else {
                // Jika belum, paksa ke halaman buat toko
                return redirect()->route('penjual.create_toko');
            }
        }

        // 3. Jika role adalah PEMBELI
        if ($user->role === 'pembeli') {
            // Untuk e-commerce, pembeli lebih baik diarahkan ke homepage
            // untuk langsung melihat produk, bukan ke dashboard kosong.
            return redirect()->route('home');
        }

        // 4. Fallback, jika role tidak terdefinisi (seharusnya tidak terjadi)
        // Kita logout paksa untuk keamanan
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('login')->with('error', 'Role pengguna tidak valid. Silakan hubungi admin.');
    }
}

