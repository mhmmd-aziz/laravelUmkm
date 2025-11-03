<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Penjual\DashboardController as PenjualDashboardController;
use App\Http\Controllers\Penjual\TokoController as PenjualTokoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukPublikController;
use App\Http\Controllers\Penjual\ProdukController as PenjualProdukController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Pembeli\AlamatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Pembeli\PesananController as PembeliPesananController;
use App\Http\Controllers\Penjual\PesananController as PenjualPesananController;
use App\Http\Controllers\Penjual\OmsetController;
use App\Http\Controllers\Penjual\AiInsightController;
// --- TAMBAHAN BARU ---
use App\Http\Controllers\AiChatbotController;
// --- BATAS TAMBAHAN ---
use Gemini\Laravel\Facades\Gemini;
use Gemini\Client;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == RUTE PUBLIK ==
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk/{produk:slug}', [ProdukPublikController::class, 'show'])->name('produk.show');

// --- TAMBAHAN BARU: Rute AI Chatbot Publik ---
Route::post('/ai-chatbot-query', [AiChatbotController::class, 'query'])->name('ai.chatbot.query');
// --- BATAS TAMBAHAN ---


// == RUTE KERANJANG BELANJA (CART) ==
Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index'); 
    Route::post('/tambah', [CartController::class, 'store'])->name('store'); 
    Route::post('/update/{itemId}', [CartController::class, 'update'])->name('update'); 
    Route::get('/hapus/{itemId}', [CartController::class, 'destroy'])->name('destroy'); 
    Route::get('/kosongkan', [CartController::class, 'clear'])->name('clear'); 
});

Route::get('/pesanan-berhasil', [CheckoutController::class, 'success'])->name('checkout.success')
    ->middleware(['auth', 'verified', 'role:pembeli']);


// == RUTE AUTENTIKASI (LOGIN, REGISTER, DLL) ==
require __DIR__.'/auth.php';


// == RUTE 'PENGARAH' SETELAH LOGIN ==
Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// == GRUP RUTE ADMIN ==
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});


// == GRUP RUTE PENJUAL ==
Route::middleware(['auth', 'verified', 'role:penjual'])->prefix('penjual')->name('penjual.')->group(function () {
    
    Route::middleware(['toko.exists'])->group(function () {
        Route::get('/dashboard', [PenjualDashboardController::class, 'index'])->name('dashboard');
        Route::resource('produk', PenjualProdukController::class);

        Route::get('/pesanan', [PenjualPesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{transaksi}', [PenjualPesananController::class, 'show'])->name('pesanan.show');
        Route::patch('/pesanan/{transaksi}', [PenjualPesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
        
        Route::get('/laporan-omset', [OmsetController::class, 'index'])->name('omset.index');
        Route::get('/laporan-omset/ekspor', [OmsetController::class, 'export'])->name('omset.export');

        Route::get('/ai-insight', [AiInsightController::class, 'index'])->name('ai.index');
        Route::post('/ai-insight/get', [AiInsightController::class, 'getInsight'])->name('ai.getInsight');
        
        Route::post('/ai-generate-deskripsi', [AiInsightController::class, 'generateDeskripsi'])->name('ai.generateDeskripsi');
    });

    Route::middleware(['toko.doesnt_exist'])->group(function () {
        Route::get('/buat-toko', [PenjualTokoController::class, 'create'])->name('create_toko');
        Route::post('/buat-toko', [PenjualTokoController::class, 'store'])->name('store_toko');
    });
    
});


// == GRUP RUTE PEMBELI ==
Route::middleware(['auth', 'verified', 'role:pembeli'])->prefix('pembeli')->name('pembeli.')->group(function () {
    
    Route::get('/dashboard', function () {
        return redirect()->route('pembeli.pesanan.index'); 
    })->name('dashboard');
    
    Route::resource('alamat', AlamatController::class)->except(['index', 'show']);
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/pesanan', [PembeliPesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{transaksi}', [PembeliPesananController::class, 'show'])->name('pesanan.show');
});


// == RUTE PROFILE (Bawaan Breeze) ==
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/ai-models', function () {
    // Ambil daftar model dari Gemini API
    $models = Gemini::models()->list();

    // Tampilkan hasilnya untuk dicek
    dd($models);
});

Route::get('/test-role', function () {
    return 'Role middleware aktif!';
})->middleware(['auth', 'role:admin']);
