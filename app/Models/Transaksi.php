<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alamat_id',
        'kode_transaksi',
        'total_harga_produk',
        'biaya_pengiriman',
        'total_pembayaran',
        'metode_pembayaran',
        'status_pembayaran',
        'status_pesanan',
        'nomor_resi',
        'jasa_pengiriman',
        'catatan_pembeli',
        
    ];

     protected $casts = [
        'alamat_pengiriman_json' => 'array', // Otomatis konversi JSON ke Array
    ];
    /**
     * Mendapatkan User (pembeli) yang melakukan transaksi.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan alamat pengiriman transaksi.
     */
    public function alamat()
    {
        return $this->belongsTo(Alamat::class);
    }

    /**
     * Mendapatkan semua item/detail dalam transaksi ini.
     */
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
