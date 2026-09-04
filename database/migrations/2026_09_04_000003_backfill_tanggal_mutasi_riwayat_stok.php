<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Record lama dibuat sebelum kolom tanggal_mutasi tersedia.
        // Untuk data tersebut, gunakan created_at sebagai tanggal mutasi.
        if (Schema::hasColumn('riwayat_stok', 'tanggal_mutasi')) {
            DB::statement("UPDATE riwayat_stok SET tanggal_mutasi = DATE(created_at) WHERE tanggal_mutasi IS NULL AND created_at IS NOT NULL");
        }
    }

    public function down(): void
    {
        // Tidak mengosongkan kembali data historis karena tanggal hasil backfill
        // merupakan data yang valid sebagai fallback dari created_at.
    }
};
