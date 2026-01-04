<?php

namespace App\Livewire\Admin;

use App\Models\Pesanan;
use App\Services\StokService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

class DetailPesanan extends Component
{
    public Pesanan $pesanan;

    public function mount(Pesanan $pesanan): void
    {
        $this->pesanan = $pesanan->load(['meja', 'item', 'pembayaran']);
    }

    public function muat(): void
    {
        $this->pesanan->refresh()->load(['meja', 'item', 'pembayaran']);
    }

    public function verifikasiDanProses(StokService $stokService): void
    {
        try {
            $userId = Auth::id();
            if (!$userId) abort(403);

            DB::transaction(function () use ($stokService, $userId) {
                $p = Pesanan::query()
                    ->whereKey($this->pesanan->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $p->load(['item', 'pembayaran']);

                if ($p->pembayaran) {
                    $p->pembayaran->status = 'terverifikasi';
                    $p->pembayaran->diverifikasi_oleh = $userId;
                    $p->pembayaran->waktu_verifikasi = now();
                    $p->pembayaran->save();
                }

                $p->status_pembayaran = Pesanan::BAYAR_LUNAS;
                $p->status = Pesanan::STATUS_DIKONFIRMASI;
                $p->waktu_validasi = now();
                $p->save();

                $stokService->komitTerjual($p, $userId);
            });

            $this->dispatch('notyf', tipe: 'success', pesan: 'Pembayaran diverifikasi & stok dikomit.');
            $this->muat();
        } catch (ValidationException $e) {
            $msg = collect($e->errors())->flatten()->first() ?? 'Validasi gagal.';
            $this->dispatch('notyf', tipe: 'error', pesan: $msg);
        } catch (Throwable $e) {
            report($e);
            $this->dispatch('notyf', tipe: 'error', pesan: 'Terjadi error saat verifikasi. Cek storage/logs/laravel.log');
        }
    }

    public function tolakPembayaran(StokService $stokService): void
    {
        try {
            $userId = Auth::id();
            if (!$userId) abort(403);

            DB::transaction(function () use ($stokService, $userId) {
                $p = Pesanan::query()
                    ->whereKey($this->pesanan->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $p->load(['item', 'pembayaran']);

                if ($p->pembayaran) {
                    $p->pembayaran->status = 'ditolak';
                    $p->pembayaran->diverifikasi_oleh = $userId;
                    $p->pembayaran->waktu_verifikasi = now();
                    $p->pembayaran->save();
                }

                $p->status_pembayaran = Pesanan::BAYAR_DITOLAK;
                $p->status = Pesanan::STATUS_DIBATALKAN;
                $p->waktu_validasi = now();
                $p->save();

                $stokService->lepasReservasi($p, $userId);
            });

            $this->dispatch('notyf', tipe: 'info', pesan: 'Pembayaran ditolak, reservasi stok dilepas.');
            $this->muat();
        } catch (ValidationException $e) {
            $msg = collect($e->errors())->flatten()->first() ?? 'Validasi gagal.';
            $this->dispatch('toast', tipe: 'error', pesan: $msg);
        } catch (Throwable $e) {
            report($e);
            $this->dispatch('notyf', tipe: 'error', pesan: 'Terjadi error saat menolak. Cek storage/logs/laravel.log');
        }
    }

    public function render()
    {
        return view('livewire.admin.detail-pesanan')
            ->layout('components.admin-layout');
    }
}
