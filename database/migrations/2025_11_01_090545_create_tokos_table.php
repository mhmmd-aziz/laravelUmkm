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
        Schema::create('tokos', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User (Penjual)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke Provinsi (Lokasi Toko)
            $table->foreignId('provinsi_id')->constrained('provinsis')->onDelete('restrict');

            $table->string('nama_toko');
            $table->string('slug')->unique(); // Untuk URL toko (e.g., /toko/batik-jaya)
            $table->text('deskripsi')->nullable();
            $table->text('alamat_toko');
            $table->string('nomor_telepon');
            $table->string('logo_toko')->nullable(); // Path ke file logo
            $table->boolean('is_active')->default(true); // Untuk admin mem-banned/menonaktifkan toko
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokos');
    }
};
