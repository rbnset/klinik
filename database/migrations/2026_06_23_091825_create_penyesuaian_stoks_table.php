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
        Schema::create('penyesuaian_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_obat')->constrained('obat')->restrictOnDelete();
            $table->foreignId('id_pengguna')->constrained('users')->restrictOnDelete();
            $table->date('tanggal')->index();
            $table->enum('jenis', ['penambahan', 'pengurangan']);
            $table->enum('alasan', ['kadaluwarsa', 'rusak', 'hilang', 'selisih_hitung']);
            $table->unsignedSmallInteger('jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_stoks');
    }
};
