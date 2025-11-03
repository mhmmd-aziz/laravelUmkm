<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'toko_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'catatan_pembeli',
        'catatan_pembeli_per_produk',
    ];

    /**
     * Mendapatkan header transaksi.
     */
    public $timestamps = false;
    
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    /**
     * Mendapatkan info produk.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Mendapatkan info toko.
     */
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
}
