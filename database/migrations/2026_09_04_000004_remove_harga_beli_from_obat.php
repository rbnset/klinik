<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            if (Schema::hasColumn('obat', 'harga_beli')) {
                $table->dropColumn('harga_beli');
            }
        });
    }

    public function down(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            if (! Schema::hasColumn('obat', 'harga_beli')) {
                $table->unsignedInteger('harga_beli')->nullable();
            }
        });
    }
};
