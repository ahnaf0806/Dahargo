<?php

namespace App\Livewire\Admin;

use App\Models\Pesanan;
use Livewire\Component;

class NotifikasiPesananMasuk extends Component
{
    public int $jumlah = 0;
    public int $sebelumnya = 0;
    public bool $enablePoll = true;

    public function muat(bool $toast = false): void
    {
        $baru = Pesanan::query()
            ->where('status', Pesanan::STATUS_MENUNGGU)
            ->count();

        if ($toast && $baru > $this->jumlah) {
            $selisih = $baru - $this->jumlah;
            $this->dispatch('notyf',
                type: 'success',
                message: $selisih === 1
                    ? 'Pesanan baru masuk!'
                    : "{$selisih} pesanan baru masuk!"
            );
        }

        $this->jumlah = $baru;
    }

    public function mount(bool $enablePoll = true): void
    {
        $this->enablePoll = $enablePoll;

        $this->lastSeenId = session('admin_last_seen_order_id', (int) (Pesanan::max('id' ?? 0)));
        $this->badge = $this->hitungMenunggu();
    }

    public function cekPesananMasuk(): void
    {
        if (! $this->enablePoll) return;

        // contoh: status "menunggu" = pesanan baru (sesuaikan field/status di DB kamu)
        $adaBaru = Pesanan::where('id', '>', $this->lastSeenId)
            ->where('status', 'menunggu')
            ->count();

        if ($adaBaru > 0) {
            $this->dispatch('notyf', type: 'success', message: "Ada {$adaBaru} pesanan baru!");

            // update marker supaya tidak muncul berulang untuk pesanan yang sama
            $this->lastSeenId = (int) (Pesanan::max('id') ?? $this->lastSeenId);
            session(['admin_last_seen_order_id' => $this->lastSeenId]);
        }

        $this->badge = $this->hitungMenunggu();
    }

    private function hitungMenunggu(): int
    {
        return Pesanan::where('status', 'menunggu')->count();
    }

    public function render()
    {
        return view('livewire.admin.notifikasi-pesanan-masuk');
    }
}
