<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_produksi_header', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');

            // FK ke mst_mesin
            $table->foreignId('id_mesin')
                  ->constrained('mst_mesin')
                  ->restrictOnDelete();

            // FK ke mst_produk via kode_produksi (opsi A: VARCHAR)
            $table->string('kode_produksi', 20);
            $table->foreign('kode_produksi')
                  ->references('kode_produksi')
                  ->on('mst_produk')
                  ->restrictOnDelete();

            $table->unsignedInteger('slah_sisir');
            $table->unsignedInteger('pick');                    // tidak boleh 0 — dicek di validasi
            $table->decimal('panjang_pcs', 10, 2);             // tidak boleh 0 — dicek di validasi
            $table->decimal('jam_kerja', 5, 2);                // 0 < jam_kerja <= 24
            $table->decimal('efisiensi', 5, 4);                // disimpan 0.0000–1.0000
            $table->decimal('rpm_aktual', 10, 2);
            $table->unsignedTinyInteger('jumlah_mesin')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_produksi_header');
    }
};
