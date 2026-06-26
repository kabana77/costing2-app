<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrxKalkulasiResult extends Model
{
    protected $table = 'trx_kalkulasi_result';

    // PK bukan auto-increment — sama dengan id_transaksi (1-to-1)
    protected $primaryKey = 'id_transaksi';
    public $incrementing  = false;

    protected $fillable = [
        'id_transaksi',
        'output_riil_pcs',
        'total_biaya_hari',
        'cost_per_pcs',
        'cost_per_kodi',
    ];

    protected $casts = [
        'output_riil_pcs'  => 'float',
        'total_biaya_hari' => 'float',
        'cost_per_pcs'     => 'float',
        'cost_per_kodi'    => 'float',
    ];

    public function header(): BelongsTo
    {
        return $this->belongsTo(TrxProduksiHeader::class, 'id_transaksi');
    }
}
