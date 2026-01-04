<?php

namespace App\Livewire\Pelanggan;

use App\Models\Menu;
use Livewire\Attributes\On;
use Livewire\Component;

class KeranjangMini extends Component
{
    #[On('keranjangDiperbarui')]
    public function refreshKeranjang(): void
    {
        // hanya untuk trigger re-render
    }

    private function keranjang(): array
    {
        return session()->get('keranjang', []);
    }

    private function simpanKeranjang(array $keranjang): void
    {
        session()->put('keranjang', $keranjang);
        $this->dispatch('keranjangDiperbarui');
    }

    public function tambah(int $menuId): void
    {
        $menu = Menu::query()->where('aktif', true)->findOrFail($menuId);

        $stokTersedia = max(0, (int)$menu->stok_fisik - (int)$menu->stok_dipesan);

        $keranjang = $this->keranjang();
        $jumlahSaatIni = (int)($keranjang[$menuId] ?? 0);

        if ($stokTersedia <= 0 || ($jumlahSaatIni + 1) > $stokTersedia) {
            $this->dispatch('toast', tipe: 'peringatan', pesan: 'Stok tidak cukup untuk menambah.');
            return;
        }

        $keranjang[$menuId] = $jumlahSaatIni + 1;
        $this->simpanKeranjang($keranjang);
    }

    public function kurang(int $menuId): void
    {
        $keranjang = $this->keranjang();
        $jumlahSaatIni = (int)($keranjang[$menuId] ?? 0);

        if ($jumlahSaatIni <= 1) {
            unset($keranjang[$menuId]);
        } else {
            $keranjang[$menuId] = $jumlahSaatIni - 1;
        }

        $this->simpanKeranjang($keranjang);
    }

    public function hapus(int $menuId): void
    {
        $keranjang = $this->keranjang();
        unset($keranjang[$menuId]);
        $this->simpanKeranjang($keranjang);
    }

    public function kosongkan(): void
    {
        session()->forget('keranjang');
        $this->dispatch('keranjangDiperbarui');
        $this->dispatch('toast', tipe: 'info', pesan: 'Keranjang dikosongkan.');
    }

    public function render()
    {
        $keranjang = $this->keranjang();
        $ids = array_keys($keranjang);

        $daftar = collect();
        $subtotal = 0;

        if (!empty($ids)) {
            $menu = Menu::query()->whereIn('id', $ids)->get()->keyBy('id');

            foreach ($keranjang as $id => $jumlah) {
                $m = $menu->get($id);
                if (!$m) continue;

                $stokTersedia = max(0, (int)$m->stok_fisik - (int)$m->stok_dipesan);

                $totalBaris = $m->harga * (int)$jumlah;
                $subtotal += $totalBaris;

                $daftar->push([
                    'id' => $m->id,
                    'nama' => $m->nama,
                    'harga' => (int)$m->harga,
                    'jumlah' => (int)$jumlah,
                    'total_baris' => (int)$totalBaris,
                    'stok_tersedia' => $stokTersedia,
                ]);
            }
        }

        return view('livewire.pelanggan.keranjang-mini', [
            'daftar' => $daftar,
            'subtotal' => $subtotal,
            'jumlahItem' => array_sum($keranjang),
        ]);
    }
}
