<?php

use App\Http\Controllers\OtpController;
use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Penjual\DashboardController as PenjualDashboardController;
use App\Http\Controllers\Penjual\TokoController as PenjualTokoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukPublikController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Pembeli\AlamatController;
// --- INI PERUBAHANNYA ---
use App\Http\Controllers\CheckoutController; // Pastikan ini ada
// --- BATAS PERUBAHAN ---
use App\Http\Controllers\Pembeli\PesananController as PembeliPesananController;
use App\Http\Controllers\Penjual\PesananController as PenjualPesananController;
use App\Http\Controllers\Penjual\OmsetController;
use App\Http\Controllers\Penjual\AiInsightController;
use App\Http\Controllers\AiChatbotController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == RUTE PUBLIK ==
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PagesController::class, 'about'])->name('about');
Route::get('/kontak', [PagesController::class, 'kontak'])->name('kontak');
Route::post('/kontak/send', [\App\Http\Controllers\ContactController::class, 'send'])
    ->name('kontak.send');
Route::get('/faq', [PagesController::class, 'faq'])->name('faq');
Route::get('/tentang-umkm', [PagesController::class, 'tentangUmkm'])->name('tentang.umkm');
Route::get('/produk/{produk:slug}', [ProdukPublikController::class, 'show'])->name('produk.show');
Route::post('/ai-chatbot-query', [AiChatbotController::class, 'query'])->name('ai.chatbot.query');

// == RUTE PENGGANTI BAHASA ==
Route::post('/language-switch', [LanguageController::class, 'switchLang'])->name('language.switch');

// == RUTE AUTENTIKASI (LOGIN, REGISTER, DLL) ==
require __DIR__.'/auth.php';

// == RUTE 'PENGARAH' SETELAH LOGIN ==
Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// == RUTE KERANJANG ==
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/keranjang', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/keranjang/{itemId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{itemId}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/keranjang', [CartController::class, 'clear'])->name('cart.clear');
});

// == GRUP RUTE ADMIN ==
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // ... Rute admin lainnya ...
});

// == GRUP RUTE PENJUAL ==
Route::middleware(['auth', 'verified', 'role:penjual'])->prefix('penjual')->name('penjual.')->group(function () {
    
    Route::middleware(['toko.exists'])->group(function () {
        Route::get('/dashboard', [PenjualDashboardController::class, 'index'])->name('dashboard');
        Route::resource('produk', \App\Http\Controllers\Penjual\ProdukController::class);
        
        Route::get('/pesanan', [PenjualPesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{transaksi}', [PenjualPesananController::class, 'show'])->name('pesanan.show');
        Route::patch('/pesanan/{transaksi}', [PenjualPesananController::class, 'updateStatus'])->name('pesanan.updateStatus');

        Route::get('/laporan-omset', [OmsetController::class, 'index'])->name('omset.index');
        Route::get('/laporan-omset/export', [OmsetController::class, 'export'])->name('omset.export');

        Route::get('/ai-insight', [AiInsightController::class, 'index'])->name('ai.index');
        Route::post('/ai-insight/query', [AiInsightController::class, 'getInsight'])->name('ai.getInsight');
        Route::post('/ai-copywriting', [AiInsightController::class, 'generateDeskripsi'])->name('ai.generateDeskripsi');
    });

    Route::middleware(['toko.doesnt_exist'])->group(function () {
        Route::get('/buat-toko', [PenjualTokoController::class, 'create'])->name('create_toko');
        Route::post('/buat-toko', [PenjualTokoController::class, 'store'])->name('store_toko');
    });
});

// == GRUP RUTE PEMBELI ==
Route::middleware(['auth', 'verified', 'role:pembeli'])->prefix('pembeli')->name('pembeli.')->group(function () {
    
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');
    
    Route::resource('alamat', AlamatController::class);

    // --- INI DIA PERBAIKANNYA ---
    // Hapus route 'store' (POST) yang lama
    // Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Ganti dengan route 'process' (GET)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process'); 
    // --- BATAS PERBAIKAN ---

    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/pesanan', [PembeliPesananController::class, 'index'])->name('pesanan.index');
    // Route::get('/pesanan/{transaksi}', [PembeliPesananController::class, 'show'])->name('pesanan.show'); // Kita belum buat view-nya
});

// == RUTE PROFILE (Bawaan Breeze) ==
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/verify-otp', [OtpController::class, 'index'])->name('verify.otp.page');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('verify.otp');

// Resend OTP
Route::post('/resend-otp', [OtpController::class, 'resend'])->name('verify.otp.resend');
