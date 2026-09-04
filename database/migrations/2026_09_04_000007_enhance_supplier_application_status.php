<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplier', function (Blueprint $table): void {
            $table->text('alasan_penolakan')->nullable()->after('status_pengajuan');
            $table->timestamp('ditolak_at')->nullable()->after('alasan_penolakan');
            $table->timestamp('pengajuan_dapat_diajukan_lagi_at')->nullable()->after('ditolak_at');
        });
    }

    public function down(): void
    {
        Schema::table('supplier', function (Blueprint $table): void {
            $table->dropColumn([
                'alasan_penolakan',
                'ditolak_at',
                'pengajuan_dapat_diajukan_lagi_at',
            ]);
        });
    }
};
