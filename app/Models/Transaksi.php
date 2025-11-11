<?php

// --- INI DIA PERBAIKANNYA ---
namespace App\Models; 
// (Sebelumnya: namespace App;)
// --- BATAS PERBAIKAN ---

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * Tentukan field yang boleh diisi massal.
     */
    protected $fillable = [
        'user_id',
        'toko_id',
        'invoice_id',
        'metode_pembayaran',
        'total_harga',
        'ongkir',
        'total_bayar',
        'alamat_pengiriman',
        'status_transaksi',
        'nomor_resi',
        'catatan_penjual'
    ];

    /**
     * Ubah alamat_pengiriman (JSON string) menjadi array/object
     * saat diakses di Eloquent.
     */
    protected $casts = [
        'alamat_pengiriman' => 'array',
    ];

    /**
     * Relasi ke User (Pembeli).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Toko (Penjual).
     */
    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }

    /**
     * Relasi ke Detail Transaksi (Item-item).
     */
    public function detailTransaksis(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}