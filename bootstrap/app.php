<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // --- INI ADALAH PERBAIKANNYA ---
        // 1. Daftarkan Alias Middleware Kustom Anda di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'toko.exists' => \App\Http\Middleware\EnsureTokoExists::class,
            'toko.doesnt_exist' => \App\Http\Middleware\EnsureTokoDoesntExist::class,
        ]);

        // 2. Hapus middleware Inertia yang salah dari grup 'web'
        $middleware->web(remove: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
        // --- BATAS PERBAIKAN ---

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

