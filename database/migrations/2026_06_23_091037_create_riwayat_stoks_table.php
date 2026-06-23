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
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_obat')->constrained('obat')->restrictOnDelete();
            $table->enum('jenis_transaksi', ['masuk', 'keluar'])->index();
            $table->unsignedSmallInteger('jumlah');
            $table->unsignedSmallInteger('stok_sebelum');
            $table->unsignedSmallInteger('stok_sesudah');
            $table->string('referensi_transaksi', 100)->nullable()->index();
            $table->timestamps(); // created_at berfungsi sebagai tanggal mutasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_stoks');
    }
};
