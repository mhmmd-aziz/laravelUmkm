<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'gambar',
    ];

    /**
     * Mendapatkan semua produk dalam kategori ini.
     */
    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}
