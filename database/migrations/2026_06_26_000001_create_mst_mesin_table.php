<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mst_mesin', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mesin', 10)->unique();
            $table->string('nama_mesin', 50);
            $table->decimal('rpm_default', 10, 2)->default(0);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mst_mesin');
    }
};
