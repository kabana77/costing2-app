<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrxBiayaDetail extends Model
{
    protected $table = 'trx_biaya_detail';

    protected $fillable = [
        'id_transaksi',
        'komponen_biaya',
        'nilai_per_hari',
    ];

    protected $casts = [
        'nilai_per_hari' => 'float',
    ];

    // Nama komponen biaya yang valid — dipakai CostingService saat insert
    public const KOMPONEN = [
        'Upah Operator',
        'Listrik Mesin',
        'Maintenance',
        'Penyusutan Mesin',
        'Biaya Lain',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(TrxProduksiHeader::class, 'id_transaksi');
    }
}
