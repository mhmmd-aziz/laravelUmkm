<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Kita tidak perlu meng-import Volt lagi jika tidak dipakai
// use Livewire\Volt\Volt; 

class VoltServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // HAPUS 'Volt::boot();' DARI SINI
        // Biarkan kosong
    }
}

