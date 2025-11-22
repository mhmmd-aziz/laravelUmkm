<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- TAMBAHKAN INI
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'toko_id',
        'kategori_id',
        'nama_produk',
        'slug',
        'deskripsi_singkat',
        'deskripsi_lengkap',
        'harga',
        'stok',
        'berat_gram',
        'gambar_produk_utama',
    ];

    /**
     * Mengganti 'id' dengan 'slug' untuk pencarian route-model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Relasi ke Toko (Satu Produk dimiliki oleh satu Toko).
     */
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    /**
     * Relasi ke Kategori (Satu Produk masuk dalam satu Kategori).
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    // --- INI DIA PERBAIKANNYA (Fungsi yang hilang) ---
    /**
     * Relasi ke Detail Transaksi (Satu Produk bisa ada di banyak Detail Transaksi).
     */
    public function detailTransaksis(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class);
    }
    // --- BATAS PERBAIKAN ---
}