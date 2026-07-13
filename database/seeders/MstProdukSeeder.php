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
            ['kode_produksi' => 'DBY.53 JB', 'konstruksi' => '20s TR x 20/10 TR BK', 'panjang_default' => 312, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'DBY.53 JR', 'konstruksi' => '20s TR x 20/10 TR BK', 'panjang_default' => 180, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'DBY.58', 'konstruksi' => '20s TR x 20/10 TR BK', 'panjang_default' => 232, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'DBY.59', 'konstruksi' => '20s TR x 20/4 TR BK', 'panjang_default' => 230, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'DBY.60', 'konstruksi' => '30/2 TR x 30/2 TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.001', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.100', 'konstruksi' => '45s TR x 60s TR BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.102', 'konstruksi' => '60/2 TR x 45s TR BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.127', 'konstruksi' => '55/2 TR x 45s TR BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.128', 'konstruksi' => '55/2 TR x 45s TR BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.129', 'konstruksi' => '55/2 TR x 45s TR BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.130', 'konstruksi' => '30s TR x 45s TR BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.131', 'konstruksi' => '60/2 TR x 45s TR+120 D Viscose BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.301', 'konstruksi' => '55/2 TR x 40s RY BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.303', 'konstruksi' => '55/2 TR x 45s TR BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.304', 'konstruksi' => '55/2 TR x 40s Bamboo BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.305', 'konstruksi' => '55/2 TR x 45s TR BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.307', 'konstruksi' => '60/2 TB x 40s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'JQ.308', 'konstruksi' => '55/2 TR x 40s RT BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.01', 'konstruksi' => '40/2 TR x 40s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.02', 'konstruksi' => '30s PR x 30s PR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.03', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.03 BNW', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.04', 'konstruksi' => '40s TR x 40s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.05', 'konstruksi' => '45s TR x 45s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.46', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.46 PG', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.47', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.48', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.49', 'konstruksi' => '30s TR+40/2 TR Kpr x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.50', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 186, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.53', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.54', 'konstruksi' => '45s TR x 55s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.55', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.56', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.58', 'konstruksi' => '45s TR x 55/2 TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.59', 'konstruksi' => '45s TR x 45s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.60', 'konstruksi' => '55s TR x 55s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.63', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.64', 'konstruksi' => '15s TR x 55s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.67', 'konstruksi' => '30s TR x 30s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.68', 'konstruksi' => '30s TR x 30s TR + Metalik BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.69', 'konstruksi' => '20s TR x 45s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P.70 ESP', 'konstruksi' => '30s TR + Metalik x 30s TR', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'SB.601', 'konstruksi' => '30/2 TR x 30s TR/4 BK', 'panjang_default' => 220, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'SK.41', 'konstruksi' => '40s TR x 40s TR BK', 'panjang_default' => 215, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'T.101', 'konstruksi' => '30/2 TR x 30/4 TR BK', 'panjang_default' => 200, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'T.107', 'konstruksi' => '52/2 Acrylic x 52/2 Acrylic BK', 'panjang_default' => 200, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'T.111', 'konstruksi' => '30/2 TR x (30/2)/3 TR BK', 'panjang_default' => 200, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'T.112', 'konstruksi' => '60/2 TB x 40s TB BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'T.113', 'konstruksi' => '60/2 TB x 40s TB BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'T.114', 'konstruksi' => '60/2 RT x 40s RT BK', 'panjang_default' => 218, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'T.116', 'konstruksi' => '30/2 TR x 30s TR/4 BK', 'panjang_default' => 154, 'created_at' => $now, 'updated_at' => $now],
            // keep the previously referenced codes (normalized) so seeders that rely on them still work
            ['kode_produksi' => 'P046', 'konstruksi' => 'Kain polos 40s / 40s', 'panjang_default' => 215.00, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P031', 'konstruksi' => 'Kain drill 30s / 30s', 'panjang_default' => 200.00, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P052', 'konstruksi' => 'Kain oxford 20s / 20s', 'panjang_default' => 230.00, 'created_at' => $now, 'updated_at' => $now],
            ['kode_produksi' => 'P018', 'konstruksi' => 'Kain sarung 2x1 twill', 'panjang_default' => 180.00, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
