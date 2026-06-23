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
        Schema::create('penerimaan_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pembelian_obat')->constrained('pembelian_obat')->restrictOnDelete();
            $table->string('nomor_faktur', 100)->unique()->index(); // Index krusial untuk validasi faktur
            $table->date('tanggal_terima')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_obats');
    }
};
