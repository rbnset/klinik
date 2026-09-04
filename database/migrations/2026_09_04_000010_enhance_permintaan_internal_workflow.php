<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This migration is intentionally idempotent for columns that may already
        // exist in older versions of the internal-request workflow.
        Schema::table('permintaan_obat', function (Blueprint $table): void {
            $table->string('status', 30)->default('pending')->change();

            if (! Schema::hasColumn('permintaan_obat', 'disetujui_at')) {
                $table->timestamp('disetujui_at')->nullable();
            }

            if (! Schema::hasColumn('permintaan_obat', 'disetujui_oleh')) {
                $table->foreignId('disetujui_oleh')->nullable();
            }

            if (! Schema::hasColumn('permintaan_obat', 'diserahkan_at')) {
                $table->timestamp('diserahkan_at')->nullable();
            }

            if (! Schema::hasColumn('permintaan_obat', 'diserahkan_oleh')) {
                $table->foreignId('diserahkan_oleh')->nullable();
            }

            if (! Schema::hasColumn('permintaan_obat', 'dikonfirmasi_at')) {
                $table->timestamp('dikonfirmasi_at')->nullable();
            }

            if (! Schema::hasColumn('permintaan_obat', 'dikonfirmasi_oleh')) {
                $table->foreignId('dikonfirmasi_oleh')->nullable();
            }

            if (! Schema::hasColumn('permintaan_obat', 'catatan_gudang')) {
                $table->text('catatan_gudang')->nullable();
            }

            if (! Schema::hasColumn('permintaan_obat', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable();
            }
        });

        // Add foreign keys only when they are not already present. This matters
        // when an earlier migration/version created some of these columns.
        foreach (['disetujui_oleh', 'diserahkan_oleh', 'dikonfirmasi_oleh'] as $column) {
            if (Schema::hasColumn('permintaan_obat', $column) && ! $this->hasForeignKey($column)) {
                Schema::table('permintaan_obat', function (Blueprint $table) use ($column): void {
                    $table->foreign($column)
                        ->references('id')
                        ->on('users')
                        ->nullOnDelete();
                });
            }
        }

        DB::table('permintaan_obat')->where('status', 'disetujui')->update([
            'disetujui_at' => DB::raw('COALESCE(updated_at, CURRENT_TIMESTAMP)'),
        ]);
    }

    private function hasForeignKey(string $column): bool
    {
        $database = DB::getDatabaseName();

        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', 'permintaan_obat')
            ->where('COLUMN_NAME', $column)
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->exists();
    }

    public function down(): void
    {
        foreach (['disetujui_oleh', 'diserahkan_oleh', 'dikonfirmasi_oleh'] as $column) {
            if (Schema::hasColumn('permintaan_obat', $column) && $this->hasForeignKey($column)) {
                Schema::table('permintaan_obat', function (Blueprint $table) use ($column): void {
                    $table->dropForeign([$column]);
                });
            }
        }

        Schema::table('permintaan_obat', function (Blueprint $table): void {
            $columns = [
                'disetujui_at',
                'disetujui_oleh',
                'diserahkan_at',
                'diserahkan_oleh',
                'dikonfirmasi_at',
                'dikonfirmasi_oleh',
                'catatan_gudang',
                'alasan_penolakan',
            ];

            $existing = array_values(array_filter(
                $columns,
                fn (string $column): bool => Schema::hasColumn('permintaan_obat', $column),
            ));

            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });

        Schema::table('permintaan_obat', function (Blueprint $table): void {
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'dibatalkan'])
                ->default('pending')
                ->change();
        });
    }
};
