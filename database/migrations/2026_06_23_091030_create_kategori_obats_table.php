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
        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kategori_obat')->constrained('kategori_obat')->restrictOnDelete();
            $table->string('kode_obat', 50)->unique()->index(); // Wajib di-index karena sering dicari barcode
            $table->string('nama_obat', 150)->index();
            $table->string('satuan', 50);
            $table->unsignedSmallInteger('stok')->default(0);
            $table->unsignedInteger('harga_beli');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_obats');
    }
};
