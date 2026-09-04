<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('permintaan_obat', 'ditolak_at')) {
            Schema::table('permintaan_obat', fn (Blueprint $table) => $table->timestamp('ditolak_at')->nullable()->after('alasan_penolakan'));
        }

        if (! Schema::hasColumn('permintaan_obat', 'ditolak_oleh')) {
            Schema::table('permintaan_obat', fn (Blueprint $table) => $table->foreignId('ditolak_oleh')->nullable()->after('ditolak_at'));
        }

        if (Schema::hasColumn('permintaan_obat', 'ditolak_oleh') && ! $this->hasForeignKey('ditolak_oleh')) {
            Schema::table('permintaan_obat', function (Blueprint $table): void {
                $table->foreign('ditolak_oleh')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    private function hasForeignKey(string $column): bool
    {
        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'permintaan_obat')
            ->where('COLUMN_NAME', $column)
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->exists();
    }

    public function down(): void
    {
        if (Schema::hasColumn('permintaan_obat', 'ditolak_oleh') && $this->hasForeignKey('ditolak_oleh')) {
            Schema::table('permintaan_obat', fn (Blueprint $table) => $table->dropForeign(['ditolak_oleh']));
        }

        $columns = array_values(array_filter(['ditolak_oleh', 'ditolak_at'], fn (string $column) => Schema::hasColumn('permintaan_obat', $column)));
        if ($columns !== []) {
            Schema::table('permintaan_obat', fn (Blueprint $table) => $table->dropColumn($columns));
        }
    }
};
