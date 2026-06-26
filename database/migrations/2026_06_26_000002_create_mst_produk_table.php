<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_produk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produksi', 20)->unique();
            $table->string('konstruksi', 100)->nullable();
            $table->decimal('panjang_default', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_produk');
    }
};
