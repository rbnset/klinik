<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE permintaan_obat
            MODIFY COLUMN status
            ENUM(
                'pending',
                'disetujui',
                'ditolak',
                'dibatalkan'
            )
            DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE permintaan_obat
            MODIFY COLUMN status
            ENUM(
                'pending',
                'disetujui',
                'ditolak'
            )
            DEFAULT 'pending'
        ");
    }
};
