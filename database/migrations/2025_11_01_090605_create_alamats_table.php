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
        Schema::create('alamats', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User (Pembeli)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('label_alamat'); // Cth: Rumah, Kantor, Kos
            $table->string('nama_penerima');
            $table->string('nomor_telepon');
            $table->text('alamat_lengkap');
            $table->string('provinsi'); // Ini bisa diisi manual atau nanti pakai API RajaOngkir
            $table->string('kota_kabupaten');
            $table->string('kecamatan');
            $table->string('kode_pos');
            $table->boolean('is_default')->default(false); // Alamat utama
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamats');
    }
};
