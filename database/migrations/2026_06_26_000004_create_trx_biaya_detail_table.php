<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trx_biaya_detail', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_transaksi')
                  ->constrained('trx_produksi_header')
                  ->cascadeOnDelete();  // hapus header → hapus 5 baris biaya

            // Nilai enum-like: Upah Operator | Listrik Mesin | Maintenance | Penyusutan Mesin | Biaya Lain
            $table->string('komponen_biaya', 50);
            $table->decimal('nilai_per_hari', 15, 2)->default(0); // tidak boleh negatif — dicek di validasi

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trx_biaya_detail');
    }
};
