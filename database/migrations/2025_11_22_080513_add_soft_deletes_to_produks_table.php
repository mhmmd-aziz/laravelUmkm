<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('produks', function (Blueprint $table) {
            // Menambahkan kolom deleted_at
            $table->softDeletes(); 
        });
    }

    public function down()
    {
        Schema::table('produks', function (Blueprint $table) {
            // Menghapus kolom jika rollback
            $table->dropSoftDeletes();
        });
    }
};