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
        Schema::create('detail_pembelian_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pembelian_obat')->constrained('pembelian_obat')->cascadeOnDelete();
            $table->foreignId('id_obat')->constrained('obat')->restrictOnDelete();
            $table->unsignedSmallInteger('jumlah_pesan');
            $table->unsignedInteger('harga_satuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian_obats');
    }
};
