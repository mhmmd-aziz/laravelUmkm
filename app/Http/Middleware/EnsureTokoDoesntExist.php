<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokoDoesntExist
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini memastikan Penjual BELUM memiliki toko.
     * Hanya digunakan untuk halaman 'buat-toko'.
     * Jika SUDAH punya toko, akan diarahkan ke 'dashboard'.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Cek jika user adalah 'penjual' DAN 'toko_id' nya SUDAH ADA (not null)
        if ($user->role === 'penjual' && !is_null($user->toko)) {
            // Paksa ke dashboard penjual karena sudah punya toko
            return redirect()->route('penjual.dashboard');
        }

        return $next($request);
    }
}

