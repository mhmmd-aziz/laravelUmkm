<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Tambahkan 'role' dari Langkah 1
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- TAMBAHKAN RELASI DI BAWAH INI ---

    /**
     * Mendapatkan toko jika user adalah 'penjual'.
     */
    public function toko()
    {
        return $this->hasOne(Toko::class);
    }

    /**
     * Mendapatkan semua alamat jika user adalah 'pembeli'.
     */
    public function alamats()
    {
        return $this->hasMany(Alamat::class);
    }

    /**
     * Mendapatkan semua transaksi jika user adalah 'pembeli'.
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
