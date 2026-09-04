<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('riwayat_stok')) {
            return;
        }

        // Lengkapi data lama yang dibuat sebelum kolom traceability tersedia.
        DB::table('riwayat_stok')
            ->whereNull('tanggal_mutasi')
            ->whereNotNull('created_at')
            ->update([
                'tanggal_mutasi' => DB::raw('DATE(created_at)'),
            ]);

        $rows = DB::table('riwayat_stok')
            ->select(['id', 'referensi_transaksi', 'referensi_tipe', 'referensi_id'])
            ->where(function ($query) {
                $query->whereNull('referensi_tipe')
                    ->orWhereNull('referensi_id');
            })
            ->get();

        foreach ($rows as $row) {
            $reference = strtoupper(trim((string) $row->referensi_transaksi));

            if (! preg_match('/^(GR|REQ|ADJ)-0*(\d+)$/', $reference, $matches)) {
                continue;
            }

            $type = match ($matches[1]) {
                'GR' => 'penerimaan',
                'REQ' => 'permintaan',
                'ADJ' => 'penyesuaian',
            };

            DB::table('riwayat_stok')
                ->where('id', $row->id)
                ->update([
                    'referensi_tipe' => $row->referensi_tipe ?: $type,
                    'referensi_id' => $row->referensi_id ?: (int) $matches[2],
                ]);
        }
    }

    public function down(): void
    {
        // Data hasil backfill tidak dihapus karena merupakan metadata audit yang valid.
    }
};
