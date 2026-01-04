<?php

namespace App\Livewire\Pelanggan;

use App\Models\Pesanan;
use Livewire\Component;

class StatusPesanan extends Component
{
    public string $kode;
    public Pesanan $pesanan;

    public function mount(string $kode)
    {
        $this->kode = $kode;
        $this->muat();
    }

    public function muat(): void
    {
        $tokenTamu = request()->cookie('token_tamu');

        $q = Pesanan::query()
            ->with(['meja', 'item', 'pembayaran'])
            ->where('kode', $this->kode);

        // proteksi sederhana: kalau token_tamu ada di pesanan, harus sama cookie
        if ($tokenTamu) {
            $q->where('token_tamu', $tokenTamu);
        }

        $this->pesanan = $q->firstOrFail();
    }

    public function render()
    {
        return view('livewire.pelanggan.status-pesanan')
            ->layout('pelanggan.layouts.pelanggan', ['judul' => 'Status Pesanan']);
    }
}
