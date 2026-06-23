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
        Schema::create('permintaan_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengguna')->constrained('users')->restrictOnDelete();
            $table->date('tanggal_permintaan')->index();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending')->index();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_obats');
    }
};
