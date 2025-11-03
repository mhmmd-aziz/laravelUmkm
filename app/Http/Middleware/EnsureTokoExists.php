<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokoExists
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini memastikan Penjual SUDAH memiliki toko.
     * Jika BELUM, akan diarahkan ke halaman 'buat-toko'.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Cek jika user adalah 'penjual' DAN 'toko_id' nya KOSONG (null)
        if ($user->role === 'penjual' && is_null($user->toko)) {
            
            // Jika user mencoba mengakses halaman 'buat-toko', biarkan saja
            if ($request->routeIs('penjual.create_toko') || $request->routeIs('penjual.store_toko')) {
                return $next($request);
            }
            
            // Jika mencoba mengakses halaman lain, paksa ke 'buat-toko'
            return redirect()->route('penjual.create_toko')->with('warning', 'Anda harus membuat toko terlebih dahulu untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}

