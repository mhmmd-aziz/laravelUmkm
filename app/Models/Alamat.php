<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label_alamat',
        'nama_penerima',
        'nomor_telepon',
        'alamat_lengkap',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'kode_pos',
        'is_default',
    ];

    /**
     * Mendapatkan User (pembeli) pemilik alamat ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
