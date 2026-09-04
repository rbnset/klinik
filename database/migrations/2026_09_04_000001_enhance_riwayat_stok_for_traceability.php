<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->string('referensi_tipe', 50)->nullable()->after('referensi_transaksi')->index();
            $table->unsignedBigInteger('referensi_id')->nullable()->after('referensi_tipe')->index();
            $table->text('keterangan')->nullable()->after('referensi_id');
            $table->date('tanggal_mutasi')->nullable()->after('keterangan')->index();
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->dropIndex(['referensi_tipe']);
            $table->dropIndex(['referensi_id']);
            $table->dropIndex(['tanggal_mutasi']);
            $table->dropColumn(['referensi_tipe', 'referensi_id', 'keterangan', 'tanggal_mutasi']);
        });
    }
};
