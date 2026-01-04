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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();

            $table->string('metode');
            $table->unsignedInteger('jumlah');
            $table->string('status')->default('menunggu');

            $table->string('path_bukti')->nullable();
            $table->string('no_referensi')->nullable();
            $table->timestamp('waktu_bayar')->nullable();

            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('waktu_verifikasi')->nullable();
            $table->text('catatan_admin')->nullable();

            $table->timestamps();

            $table->unique('pesanan_id');
            $table->index(['status', 'metode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
