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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Toko (Penjual)
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            
            // Relasi ke Kategori
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('restrict');

            $table->string('nama_produk');
            $table->string('slug')->unique();
            $table->text('deskripsi_singkat')->nullable();
            $table->longText('deskripsi_lengkap')->nullable();
            
            // Gunakan decimal untuk harga yang presisi
            $table->decimal('harga', 15, 2); // Hingga 1 Triliun (15 digit, 2 desimal)
            
            // Untuk manajemen stok
            $table->integer('stok')->default(0);
            
            // Untuk perhitungan ongkir
            $table->integer('berat_gram')->default(1000); // Berat dalam gram

            // Gambar utama produk (bisa array JSON jika ingin multiple, tapi string lebih simpel)
            $table->string('gambar_produk_utama');
            // $table->json('galeri_gambar')->nullable(); // Opsional untuk galeri

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
