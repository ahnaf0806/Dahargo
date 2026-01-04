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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_menu_id')->constrained('kategori_menu')->cascadeOnDelete();

            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('harga');
            $table->string('path_foto')->nullable();
            $table->boolean('aktif')->default(true);

            $table->unsignedInteger('stok_fisik')->default(0);
            $table->unsignedInteger('stok_dipesan')->default(0);
            $table->unsignedInteger('ambang_stok_rendah')->default(5);

            $table->timestamps();

            $table->index(['aktif', 'kategori_menu_id']);
            $table->index(['stok_fisik', 'stok_dipesan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
