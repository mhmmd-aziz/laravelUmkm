<?php

// --- INI DIA PERBAIKANNYA ---
namespace App\Models;
// (Sebelumnya: namespace App;)
// --- BATAS PERBAIKAN ---

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Toko extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provinsi_id',
        'nama_toko',
        'slug',
        'deskripsi',
        'alamat_toko',
        'nomor_telepon',
        'logo_toko',
        'is_active',
    ];

    /**
     * Relasi ke User (Satu Toko dimiliki oleh satu User).
     */
    public function user(): BelSqueaksTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Provinsi (Satu Toko berlokasi di satu Provinsi).
     */
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class);
    }

    /**
     * Relasi ke Produk (Satu Toko memiliki banyak Produk).
     */
    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class);
    }

    /**
     * Relasi ke Transaksi (Satu Toko memiliki banyak Transaksi).
     */
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}