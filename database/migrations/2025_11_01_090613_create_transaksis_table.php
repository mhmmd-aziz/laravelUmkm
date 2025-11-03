<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User (Pembeli)
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            
            // Relasi ke Alamat Pengiriman
            $table->foreignId('alamat_id')->constrained('alamats')->onDelete('restrict');

            $table->string('kode_transaksi')->unique(); // Cth: INV/2025/11/01/123
            $table->decimal('total_harga_produk', 15, 2);
            $table->decimal('biaya_pengiriman', 15, 2)->default(0);
            $table->decimal('total_pembayaran', 15, 2);

            $table->string('metode_pembayaran')->nullable();
            $table->string('status_pembayaran')->default('pending'); // pending, paid, failed, expired
            
            // Status pesanan sesuai permintaan Anda
            $table->string('status_pesanan')->default('menunggu_konfirmasi'); // menunggu_konfirmasi, dikemas, dikirim, selesai, dibatalkan
            
            $table->string('nomor_resi')->nullable();
            $table->string('jasa_pengiriman')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
