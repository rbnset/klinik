<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penerimaan_obat', function (Blueprint $table) {
            $table->timestamp('stok_diposting_at')->nullable()->after('tanggal_terima');
        });

        Schema::table('permintaan_obat', function (Blueprint $table) {
            $table->timestamp('stok_diposting_at')->nullable()->after('keterangan');
        });

        Schema::table('penyesuaian_stok', function (Blueprint $table) {
            $table->timestamp('stok_diposting_at')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('penerimaan_obat', function (Blueprint $table) {
            $table->dropColumn('stok_diposting_at');
        });

        Schema::table('permintaan_obat', function (Blueprint $table) {
            $table->dropColumn('stok_diposting_at');
        });

        Schema::table('penyesuaian_stok', function (Blueprint $table) {
            $table->dropColumn('stok_diposting_at');
        });
    }
};
