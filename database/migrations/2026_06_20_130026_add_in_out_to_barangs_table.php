<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->integer('total_in')->default(0)->after('stock');
            $table->integer('total_out')->default(0)->after('total_in');
        });

        // Sync total_in dari data barang_masuk yang sudah ada
        DB::statement('
            UPDATE barangs b
            SET total_in = (
                SELECT COALESCE(SUM(jumlah), 0)
                FROM barang_masuk bm
                WHERE bm.barang_id = b.id
            )
        ');

        // Sync total_out dari data barang_keluar yang sudah ada
        DB::statement('
            UPDATE barangs b
            SET total_out = (
                SELECT COALESCE(SUM(jumlah), 0)
                FROM barang_keluar bk
                WHERE bk.barang_id = b.id
            )
        ');
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['total_in', 'total_out']);
        });
    }
};
