<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Mendapatkan User (penjual) pemilik toko ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan provinsi lokasi toko.
     */
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    /**
     * Mendapatkan semua produk yang dijual toko ini.
     */
    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}
