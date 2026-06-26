<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MstMesin extends Model
{
    protected $table = 'mst_mesin';

    protected $fillable = [
        'kode_mesin',
        'nama_mesin',
        'rpm_default',
        'keterangan',
    ];

    protected $casts = [
        'rpm_default' => 'float',
    ];

    /**
     * Satu mesin bisa dipakai di banyak transaksi produksi.
     */
    public function transaksi(): HasMany
    {
        return $this->hasMany(TrxProduksiHeader::class, 'id_mesin');
    }
}
