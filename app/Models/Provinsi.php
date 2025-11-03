<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
    ];

    /**
     * Mendapatkan semua toko di provinsi ini.
     */
    public function tokos()
    {
        return $this->hasMany(Toko::class);
    }
}
