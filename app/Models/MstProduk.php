<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MstProduk extends Model
{
    protected $table = 'mst_produk';

    protected $fillable = [
        'kode_produksi',
        'konstruksi',
        'panjang_default',
    ];

    protected $casts = [
        'panjang_default' => 'float',
    ];

    /**
     * Satu produk bisa ada di banyak transaksi produksi.
     */
    public function transaksi(): HasMany
    {
        return $this->hasMany(TrxProduksiHeader::class, 'kode_produksi', 'kode_produksi');
    }
}
