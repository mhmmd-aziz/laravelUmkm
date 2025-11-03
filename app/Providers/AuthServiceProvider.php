<?php

namespace App\Providers;

use App\Models\Produk; // <-- Tambahkan ini
use App\Policies\ProdukPolicy; // <-- Tambahkan ini
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Daftarkan policy produk kita
        Produk::class => ProdukPolicy::class, // <-- Tambahkan ini
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
