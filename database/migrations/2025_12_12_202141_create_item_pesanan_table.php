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
        Schema::create('item_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menu')->restrictOnDelete();

            $table->string('nama_menu_snapshot');
            $table->unsignedInteger('harga_snapshot');
            $table->unsignedInteger('jumlah');
            $table->unsignedInteger('total_baris');

            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['pesanan_id', 'menu_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pesanan');
    }
};
