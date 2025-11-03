<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan import User model
use Illuminate\Support\Facades\Hash; // Import Hash

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek jika admin sudah ada, jangan buat lagi
        if (User::where('email', 'admin@budaya.com')->doesntExist()) {
            User::create([
                'name' => 'Admin Utama',
                'email' => 'admin@budaya.com',
                'password' => Hash::make('password'), // GANTI DENGAN PASSWORD AMAN!
                'role' => 'admin',
                'email_verified_at' => now(), // Langsung verifikasi email admin
            ]);
        }
    }
}
