<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produk extends Model
{
    use HasFactory;

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
     * Mendapatkan Toko (penjual) dari produk ini.
     */
     /**
     * Relasi ke Toko (Satu produk dimiliki oleh satu Toko).
     */
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    /**
     * Mendapatkan kategori dari produk ini.
     */
    /**
     * Relasi ke Kategori (Satu produk dimiliki oleh satu Kategori).
     */
     public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    # public function detail_transaksi(): BelongsTo{
    #    return $this->belongsTo(detail_transaksi::class);
   # }


     public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
