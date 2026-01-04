<?php

namespace App\Livewire\Pelanggan;

use Livewire\Component;
use Livewire\Attributes\On;

class KeranjangBadge extends Component
{
    public int $jumlah = 0;

    public function mount(): void
    {
        $this->hitung();
    }

    #[On('keranjangDiperbarui')]
    public function hitung(): void
    {
        $this->jumlah = array_sum(session()->get('keranjang', []));
    }

    public function render()
    {
        return view('livewire.pelanggan.keranjang-badge');
    }
}