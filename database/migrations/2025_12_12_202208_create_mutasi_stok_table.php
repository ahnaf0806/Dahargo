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
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu')->cascadeOnDelete();

            $table->string('tipe');

            $table->integer('jumlah');
            $table->unsignedInteger('sebelum');
            $table->unsignedInteger('sesudah');

            $table->foreignId('pesanan_id')->nullable()->constrained('pesanan')->nullOnDelete();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->string('alasan')->nullable();

            $table->timestamps();

            $table->index(['menu_id', 'tipe']);
            $table->index(['pesanan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};
