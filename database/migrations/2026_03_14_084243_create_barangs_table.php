<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('barangs', function (Blueprint $table) {
        $table->id();
        $table->string('kode_barang')->unique();
        $table->string('name');
        $table->string('colour_number');
        $table->string('merk');
        $table->string('colour');
        $table->boolean('opened')->default(false);
        $table->integer('stock')->default(0);
        $table->integer('stock_minimum')->default(0);
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
