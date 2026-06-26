<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MstProdukSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('mst_produk')->insert([
            [
                'kode_produksi'   => 'P046',
                'konstruksi'      => 'Kain polos 40s / 40s',
                'panjang_default' => 215.00,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'kode_produksi'   => 'P031',
                'konstruksi'      => 'Kain drill 30s / 30s',
                'panjang_default' => 200.00,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'kode_produksi'   => 'P052',
                'konstruksi'      => 'Kain oxford 20s / 20s',
                'panjang_default' => 230.00,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'kode_produksi'   => 'P018',
                'konstruksi'      => 'Kain sarung 2x1 twill',
                'panjang_default' => 180.00,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ]);
    }
}
