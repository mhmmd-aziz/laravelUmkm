<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pending_users', function (Blueprint $table) {
            // Kita hapus kolom 'otp_code' karena kita sudah pakai 'otp'
            if (Schema::hasColumn('pending_users', 'otp_code')) {
                $table->dropColumn('otp_code');
            }
        });
    }

    public function down()
    {
        Schema::table('pending_users', function (Blueprint $table) {
            // Kembalikan jika di-rollback (opsional)
            $table->string('otp_code')->nullable();
        });
    }
};