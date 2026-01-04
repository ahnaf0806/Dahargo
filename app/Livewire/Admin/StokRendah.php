<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use Livewire\Component;

class StokRendah extends Component
{
    public function render()
    {
        $menu = Menu::query()
            ->where('aktif', true)
            ->get()
            ->filter(fn($m) => max(0, (int)$m->stok_fisik - (int)$m->stok_dipesan) <= (int)$m->batas_stok_rendah)
            ->sortBy(fn($m) => max(0, (int)$m->stok_fisik - (int)$m->stok_dipesan))
            ->values();

        $this->dispatch('notyf', [
            'type' => 'error',
            'message' => 'Stok tidak mencukupi'
        ]);

        return view('livewire.admin.stok-rendah', compact('menu'));
    }
}
