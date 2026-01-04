<?php

namespace App\Livewire\Pelanggan;

use App\Models\Pesanan;
use Livewire\Component;
use Livewire\WithPagination;

class RiwayatPesanan extends Component
{
    use WithPagination;

    public function render()
    {
        $tokenTamu = request()->cookie('token_tamu');

        $pesanan = Pesanan::query()
            ->with('meja')
            ->when($tokenTamu, fn ($q) => $q->where('token_tamu', $tokenTamu))
            ->orderByDesc('waktu_pesan')
            ->paginate(10);

        return view('livewire.pelanggan.riwayat-pesanan', [
            'pesanan' => $pesanan,
        ])->layout('pelanggan.layouts.pelanggan', ['judul' => 'Riwayat Pesanan']);
    }
}