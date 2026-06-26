<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_kalkulasi_result', function (Blueprint $table) {
            // PK sekaligus FK — relasi 1-to-1 dengan trx_produksi_header
            $table->unsignedBigInteger('id_transaksi')->primary();
            $table->foreign('id_transaksi')
                  ->references('id')
                  ->on('trx_produksi_header')
                  ->cascadeOnDelete();

            $table->decimal('output_riil_pcs', 15, 4);   // 4 desimal untuk presisi tinggi
            $table->decimal('total_biaya_hari', 15, 2);
            $table->decimal('cost_per_pcs', 15, 2);
            $table->decimal('cost_per_kodi', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_kalkulasi_result');
    }
};
