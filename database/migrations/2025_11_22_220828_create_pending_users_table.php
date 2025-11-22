<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('pending_users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique(); // Tetap unique biar ga double
        $table->string('password'); // Password yang sudah di-hash
        $table->string('otp_code');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_users');
    }
};
