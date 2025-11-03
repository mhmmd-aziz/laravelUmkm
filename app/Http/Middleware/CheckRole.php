<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles (cth: 'admin', 'penjual')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
       
        if (!Auth::check()) {
            return redirect('login');
        }

       
        $userRole = Auth::user()->role;

        // 3. Cek apakah role user ada di dalam daftar $roles yang diizinkan
        foreach ($roles as $role) {
            if ($userRole == $role) {
                // Jika cocok, izinkan request
                return $next($request);
            }
        }

        // 4. Jika tidak ada role yang cocok, lempar ke halaman 403 (Forbidden)
        // atau Anda bisa arahkan ke 'dashboard' utama
        // abort(403, 'Akses Ditolak. Anda tidak memiliki hak akses.');
        
        // Arahkan ke dashboard utama saja agar lebih ramah
        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki hak akses.');
    }
}

