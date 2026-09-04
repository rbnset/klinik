<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
 public function up(): void {
  Schema::table('pembayaran', function (Blueprint $table) {
   $table->string('status', 30)->default('menunggu_supplier')->after('total_bayar');
   $table->string('bukti_bayar')->nullable()->after('status');
   $table->timestamp('disetujui_supplier_at')->nullable()->after('bukti_bayar');
   $table->foreignId('disetujui_supplier_oleh')->nullable()->after('disetujui_supplier_at')->constrained('users')->nullOnDelete();
   $table->timestamp('ditolak_supplier_at')->nullable()->after('disetujui_supplier_oleh');
   $table->text('catatan_supplier')->nullable()->after('ditolak_supplier_at');
  });
  DB::table('pembayaran')->whereNull('status')->orWhere('status', 'menunggu_supplier')->update(['status' => 'disetujui_supplier']);
 }
 public function down(): void {
  Schema::table('pembayaran', function (Blueprint $table) {
   $table->dropForeign(['disetujui_supplier_oleh']);
   $table->dropColumn(['status','bukti_bayar','disetujui_supplier_at','disetujui_supplier_oleh','ditolak_supplier_at','catatan_supplier']);
  });
  DB::table('pembayaran')->whereNull('status')->orWhere('status', 'menunggu_supplier')->update(['status' => 'disetujui_supplier']);
 }
};
