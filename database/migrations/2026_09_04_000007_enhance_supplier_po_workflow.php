<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelian_obat', function (Blueprint $table): void {
            $table->string('status', 50)->default('pending')->change();
            $table->timestamp('supplier_dikonfirmasi_at')->nullable();
            $table->text('supplier_catatan')->nullable();
            $table->timestamp('harga_dikonfirmasi_at')->nullable();
            $table->foreignId('harga_dikonfirmasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ditolak_supplier_at')->nullable();
            $table->text('alasan_penolakan_supplier')->nullable();
        });

        Schema::table('detail_pembelian_obat', function (Blueprint $table): void {
            $table->unsignedInteger('harga_supplier')->nullable()->after('harga_satuan');
            $table->string('status_harga', 30)->default('belum_dikonfirmasi')->after('harga_supplier');
            $table->text('catatan_harga_supplier')->nullable()->after('status_harga');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pembelian_obat', function (Blueprint $table): void {
            $table->dropColumn(['harga_supplier', 'status_harga', 'catatan_harga_supplier']);
        });

        Schema::table('pembelian_obat', function (Blueprint $table): void {
            $table->dropForeign(['harga_dikonfirmasi_oleh']);
            $table->dropColumn([
                'supplier_dikonfirmasi_at',
                'supplier_catatan',
                'harga_dikonfirmasi_at',
                'harga_dikonfirmasi_oleh',
                'ditolak_supplier_at',
                'alasan_penolakan_supplier',
            ]);
        });
    }
};
