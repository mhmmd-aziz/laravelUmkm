<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route; // <-- Pastikan ini ada

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // Daftarkan file rute web dan api
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        
        // Daftarkan route model binding di sini
        then: function () {
            // Perbaikan typo: RouteT::class -> Route::class
            Route::model('produk', \App\Models\Produk::class);
            Route::model('transaksi', \App\Models\Transaksi::class);
            Route::model('alamat', \App\Models\Alamat::class); // Tambahkan ini untuk rute alamat
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. Daftarkan middleware global SetLocale (untuk bahasa)
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // 2. Daftarkan alias untuk middleware kustom kita
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'toko.exists' => \App\Http\Middleware\EnsureTokoExists::class,
            'toko.doesnt_exist' => \App\Http\Middleware\EnsureTokoDoesntExist::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    // HAPUS ->withTranslations() DARI SINI
    ->create();