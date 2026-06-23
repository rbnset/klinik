<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_penerimaan_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penerimaan_obat')->constrained('penerimaan_obat')->cascadeOnDelete();
            $table->foreignId('id_detail_pembelian')->constrained('detail_pembelian_obat')->restrictOnDelete();
            $table->unsignedSmallInteger('jumlah_diterima');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penerimaan_obats');
    }
};
