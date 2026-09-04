<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penerimaan_obat', function (Blueprint $table): void {
            $table->string('nomor_faktur', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('penerimaan_obat', function (Blueprint $table): void {
            $table->string('nomor_faktur', 100)->nullable(false)->change();
        });
    }
};
