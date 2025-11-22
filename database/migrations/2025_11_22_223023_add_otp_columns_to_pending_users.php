<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pending_users', function (Blueprint $table) {
            
            // 1. Cek & Tambah Kolom Role (jika belum ada)
            if (!Schema::hasColumn('pending_users', 'role')) {
                $table->string('role')->after('password')->default('pembeli');
            }

            // 2. Cek & Tambah Kolom OTP (jika belum ada)
            if (!Schema::hasColumn('pending_users', 'otp')) {
                $table->string('otp')->after('role')->nullable();
            }

            // 3. Cek & Tambah Kolom Expired (jika belum ada)
            if (!Schema::hasColumn('pending_users', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable()->after('otp');
            }
        });
    }

    public function down()
    {
        Schema::table('pending_users', function (Blueprint $table) {
            // Hapus kolom jika rollback
            $table->dropColumn(['role', 'otp', 'otp_expires_at']);
        });
    }
};