<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- TAMBAHKAN INI ---
        // Nonaktifkan pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel
        DB::table('kategoris')->truncate();

        $now = Carbon::now();
        $kategoris = [
            ['nama_kategori' => 'Batik', 'slug' => Str::slug('Batik'), 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Tenun', 'slug' => Str::slug('Tenun'), 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Ukiran Kayu', 'slug' => Str::slug('Ukiran Kayu'), 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Anyaman', 'slug' => Str::slug('Anyaman'), 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Keramik & Gerabah', 'slug' => Str::slug('Keramik & Gerabah'), 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Perhiasan Etnik', 'slug' => Str::slug('Perhiasan Etnik'), 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Wayang', 'slug' => Str::slug('Wayang'), 'created_at' => $now, 'updated_at' => $now],
            ['nama_kategori' => 'Lainnya', 'slug' => Str::slug('Lainnya'), 'created_at' => $now, 'updated_at' => $now],
        ];

        // Masukkan data baru
        DB::table('kategoris')->insert($kategoris);

        // --- TAMBAHKAN INI ---
        // Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

