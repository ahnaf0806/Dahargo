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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 24)->unique();
            $table->foreignId('meja_id')->constrained('meja')->cascadeOnDelete();

            $table->uuid('token_tamu')->nullable()->index();

            $table->unsignedInteger('subtotal')->default(0);
            $table->unsignedInteger('pajak')->default(0);
            $table->unsignedInteger('biaya_layanan')->default(0);
            $table->unsignedInteger('diskon')->default(0);
            $table->unsignedInteger('total')->default(0);

            $table->string('status')->default('menunggu');

            $table->string('metode_pembayaran');
            $table->string('status_pembayaran')->default('belum_bayar');

            $table->timestamp('waktu_pesan')->nullable();
            $table->timestamp('waktu_validasi')->nullable();

            $table->text('catatan_pelanggan')->nullable();
            $table->timestamps();

            $table->index(['status', 'status_pembayaran']);
            $table->index(['meja_id', 'waktu_pesan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
