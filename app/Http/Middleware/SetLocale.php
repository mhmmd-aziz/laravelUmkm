<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah session 'locale' ada
        if (Session::has('locale')) {
            // 2. Jika ada, set bahasa aplikasi
            App::setLocale(Session::get('locale'));
        }
        // 3. Jika tidak, biarkan Laravel menggunakan bahasa default (dari config/app.php)

        return $next($request);
    }
}