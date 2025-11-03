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
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Transaksi (Header)
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            
            // Relasi ke Produk yang dibeli
            $table->foreignId('produk_id')->constrained('produks')->onDelete('restrict'); // restrict agar produk tak bisa dihapus jika pernah terjual

            // Denormalisasi: Simpan toko_id agar dashboard penjual lebih mudah query-nya
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');

            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2); // Harga produk saat dibeli (jaga-jaga jika harga produk berubah)
            $table->decimal('subtotal', 15, 2);
            $table->string('catatan_pembeli')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
