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
        Schema::create('detail_permintaan_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_permintaan_obat')->constrained('permintaan_obat')->cascadeOnDelete();
            $table->foreignId('id_obat')->constrained('obat')->restrictOnDelete();
            $table->unsignedSmallInteger('jumlah_diminta');
            $table->unsignedSmallInteger('jumlah_disetujui')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_permintaan_obats');
    }
};
