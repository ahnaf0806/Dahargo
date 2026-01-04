<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('menu', function (Blueprint $table) {
        $table->unsignedInteger('batas_stok_rendah')->default(5)->after('stok_dipesan');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('menu', function (Blueprint $table) {
        $table->dropColumn('batas_stok_rendah');
    });
}
};
