<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MstMesinSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('mst_mesin')->insert([
            [
                'kode_mesin'  => 'RPR',
                'nama_mesin'  => 'Rapier',
                'rpm_default' => 180.00,
                'keterangan'  => 'Mesin tenun rapier standar',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'kode_mesin'  => 'AJL',
                'nama_mesin'  => 'Air Jet Loom',
                'rpm_default' => 250.00,
                'keterangan'  => 'Mesin tenun air jet berkecepatan tinggi',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'kode_mesin'  => 'WJL',
                'nama_mesin'  => 'Water Jet Loom',
                'rpm_default' => 300.00,
                'keterangan'  => 'Mesin tenun water jet',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'kode_mesin'  => 'DBL',
                'nama_mesin'  => 'Dobby Loom',
                'rpm_default' => 120.00,
                'keterangan'  => 'Mesin tenun dobby motif',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);
    }
}
