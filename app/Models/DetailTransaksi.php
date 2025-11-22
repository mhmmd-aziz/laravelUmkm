<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaksi extends Model
{
    use HasFactory;

    /**
     * Tentukan field yang boleh diisi massal.
     * (Ini yang hilang/salah sebelumnya)
     */
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'toko_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'catatan_pembeli',
    ];

    /**
     * Relasi ke Transaksi (Header).
     */
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    /**
     * Relasi ke Produk yang dibeli.
     */
    public function produk(): BelongsTo
    {
       return $this->belongsTo(Produk::class, 'produk_id')->withTrashed();
    }
}