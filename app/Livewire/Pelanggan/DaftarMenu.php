<?php

namespace App\Livewire\Pelanggan;

use App\Models\KategoriMenu;
use App\Models\Menu;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarMenu extends Component
{
    use WithPagination;

    public string $cari = '';
    public int $kategori_id = 0;

    protected $queryString = [
        'cari' => ['except' => ''],
        'kategori_id' => ['except' => 0],
    ];

    public function updatingCari(): void
    {
        $this->resetPage();
    }

    public function pilihKategori(int $id): void
    {
        $this->kategori_id = $id;
        $this->resetPage();
    }

    private function keranjang(): array
    {
        // format: [menu_id => jumlah]
        return session()->get('keranjang', []);
    }

    private function simpanKeranjang(array $keranjang): void
    {
        session()->put('keranjang', $keranjang);
        $this->dispatch('keranjangDiperbarui'); // buat KeranjangMini refresh
    }

    public function tambahKeKeranjang(int $menuId): void
    {
        $menu = Menu::query()
            ->where('aktif', true)
            ->findOrFail($menuId);

        $stokTersedia = max(0, (int)$menu->stok_fisik - (int)$menu->stok_dipesan);

        if ($stokTersedia <= 0) {
            $this->dispatch('notyf', tipe: 'error', pesan: 'Stok habis.');
            return;
        }

        $keranjang = $this->keranjang();
        $jumlahSaatIni = (int)($keranjang[$menuId] ?? 0);

        if (($jumlahSaatIni + 1) > $stokTersedia) {
            $this->dispatch('notyf', tipe: 'danger', pesan: 'Jumlah melebihi stok tersedia.');
            return;
        }

        $keranjang[$menuId] = $jumlahSaatIni + 1;
        $this->simpanKeranjang($keranjang);

        $this->dispatch('notyf', tipe: 'success', pesan: 'Ditambahkan ke keranjang.');


    }

    public function render()
    {
        $kategori = KategoriMenu::query()->orderBy('urutan')->get();

        $query = Menu::query()->where('aktif', true);

        if ($this->kategori_id) {
            $query->where('kategori_menu_id', $this->kategori_id);
        }

        if ($this->cari !== '') {
            $query->where('nama', 'like', '%' . $this->cari . '%');
        }

        $menu = $query
            ->orderBy('nama')
            ->paginate(12);

        // hitung stok tersedia per item (untuk tampilan badge)
        $menu->getCollection()->transform(function ($m) {
            $m->stok_tersedia = max(0, (int)$m->stok_fisik - (int)$m->stok_dipesan);
            return $m;
        });

        $jumlahKeranjang = array_sum($this->keranjang());

        return view('livewire.pelanggan.daftar-menu', [
            'kategori' => $kategori,
            'menu' => $menu,
            'jumlahKeranjang' => $jumlahKeranjang,
        ])->layout('pelanggan.layouts.pelanggan', [
            'judul' => 'Daftar Menu',
        ]);
    }
}
