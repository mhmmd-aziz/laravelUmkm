<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str; // <-- 1. IMPORT Str UNTUK SLUG

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('provinsis')->truncate();

        $now = Carbon::now();

        // 2. Kita ubah array-nya dari array asosiatif menjadi array biasa
        //    agar kita bisa memprosesnya dengan 'Str::slug'
        $provinsiNames = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi',
            'Sumatera Selatan', 'Bengkulu', 'Lampung', 'Kepulauan Bangka Belitung',
            'Kepulauan Riau', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah',
            'DI Yogyakarta', 'Jawa Timur', 'Banten', 'Bali', 'Nusa Tenggara Barat',
            'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah',
            'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
            'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara',
            'Gorontalo', 'Sulawesi Barat', 'Maluku', 'Maluku Utara', 'Papua Barat',
            'Papua', 'Papua Tengah', 'Papua Pegunungan', 'Papua Selatan', 'Papua Barat Daya'
        ];

        $provinsis = [];
        foreach ($provinsiNames as $nama) {
            $provinsis[] = [
                'nama_provinsi' => $nama,
                'slug' => Str::slug($nama), // <-- 3. BUAT SLUG DI SINI
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        // Masukkan data ke tabel
        DB::table('provinsis')->insert($provinsis);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

