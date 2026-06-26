<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TrxProduksiHeader extends Model
{
    protected $table = 'trx_produksi_header';

    protected $fillable = [
        'tanggal',
        'id_mesin',
        'kode_produksi',
        'slah_sisir',
        'pick',
        'panjang_pcs',
        'jam_kerja',
        'efisiensi',
        'rpm_aktual',
        'jumlah_mesin',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'panjang_pcs'  => 'float',
        'jam_kerja'    => 'float',
        'efisiensi'    => 'float',
        'rpm_aktual'   => 'float',
        'jumlah_mesin' => 'integer',
        'pick'         => 'integer',
        'slah_sisir'   => 'integer',
    ];

    public function mesin(): BelongsTo
    {
        return $this->belongsTo(MstMesin::class, 'id_mesin');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(MstProduk::class, 'kode_produksi', 'kode_produksi');
    }

    public function biayaDetail(): HasMany
    {
        return $this->hasMany(TrxBiayaDetail::class, 'id_transaksi');
    }

    public function kalkulasiResult(): HasOne
    {
        return $this->hasOne(TrxKalkulasiResult::class, 'id_transaksi');
    }
}
