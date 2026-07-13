<?php

namespace Database\Seeders;

use App\Models\MstMesin;
use App\Models\MstProduk;
use App\Services\CostingService;
use Illuminate\Database\Seeder;

class CostingSeeder extends Seeder
{
    public function run(): void
    {
        $service = new CostingService();

        $mesinRpr = MstMesin::where('kode_mesin', 'RPR')->firstOrFail();
        $mesinAjl = MstMesin::where('kode_mesin', 'AJL')->firstOrFail();

        // Try several variants so the seeder works with either dotted or non-dotted codes
        $produkP046 = MstProduk::whereIn('kode_produksi', ['P046', 'P.46'])->firstOrFail();
        $produkP031 = MstProduk::whereIn('kode_produksi', ['P031', 'P.31', 'P.03'])->firstOrFail();

        $service->calculate([
            'tanggal'       => now()->subDays(1)->toDateString(),
            'id_mesin'      => $mesinRpr->id,
            'kode_produksi' => $produkP046->kode_produksi,
            'slah_sisir'    => 8,
            'pick'          => 35,
            'panjang_pcs'   => 215.00,
            'jam_kerja'     => 7.50,
            'efisiensi'     => 88,
            'rpm_aktual'    => null,
            'jumlah_mesin'  => 2,
            'biaya'         => [
                'upah_operator'   => 450000,
                'listrik_mesin'   => 220000,
                'maintenance'     => 110000,
                'penyusutan_mesin'=> 130000,
                'biaya_lain'      => 50000,
            ],
        ]);

        $service->calculate([
            'tanggal'       => now()->toDateString(),
            'id_mesin'      => $mesinAjl->id,
            'kode_produksi' => $produkP031->kode_produksi,
            'slah_sisir'    => 10,
            'pick'          => 40,
            'panjang_pcs'   => 200.00,
            'jam_kerja'     => 8.00,
            'efisiensi'     => 90,
            'rpm_aktual'    => 240,
            'jumlah_mesin'  => 3,
            'biaya'         => [
                'upah_operator'   => 520000,
                'listrik_mesin'   => 260000,
                'maintenance'     => 140000,
                'penyusutan_mesin'=> 170000,
                'biaya_lain'      => 60000,
            ],
        ]);
    }
}
