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
            $table->foreignId('user_id')->constrained('users'); // ID Pembeli
            
            // --- INI DIA PERBAIKANNYA ---
            $table->foreignId('toko_id')->constrained('tokos'); // ID Toko (Penjual)
            // --- BATAS PERBAIKAN ---

            $table->string('invoice_id')->unique(); // Cth: INV/2025/XI/001
            $table->string('metode_pembayaran'); // Cth: 'manual_transfer_bca'
            $table->unsignedBigInteger('total_harga'); // Total harga (tanpa ongkir)
            $table->unsignedBigInteger('ongkir')->default(0); // Ongkos kirim
            $table->unsignedBigInteger('total_bayar'); // Total harga + ongkir
            
            // Ini adalah 'snapshot' alamat saat checkout, dalam format JSON
            // Agar data alamat di pesanan ini tidak berubah jika user update alamatnya
            $table->json('alamat_pengiriman'); 

            $table->string('status_transaksi'); // Cth: 'menunggu_pembayaran', 'menunggu_konfirmasi', 'dikemas', 'dikirim', 'selesai', 'dibatalkan'
            $table->string('nomor_resi')->nullable(); // Diisi oleh penjual saat 'dikirim'
            
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

