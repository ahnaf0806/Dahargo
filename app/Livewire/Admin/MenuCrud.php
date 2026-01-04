<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Menu;
use App\Models\KategoriMenu;
use Illuminate\Support\Facades\Storage;

class MenuCrud extends Component
{
    use WithPagination, WithFileUploads;

    public string $cari = '';
    public ?int $filterKategori = null;

    public bool $modal = false;
    public ?int $editId = null;

    public bool $modalKategori = false;
    public string $kategori_nama = '';
    public bool $kategori_aktif = true;

    public ?int $kategori_menu_id = null;
    public string $nama = '';
    public int $harga = 0;
    public int $stok_fisik = 0;
    public int $ambang_stok_rendah = 5;
    public int $batas_stok_rendah = 5;
    public bool $aktif = true;
    public ?string $deskripsi = null;

    public $foto; // upload
    public ?string $foto_lama = null;

    public function updatingCari() { $this->resetPage(); }
    public function updatingFilterKategori() { $this->resetPage(); }

    public function bukaTambah()
    {
        $this->reset([
            'editId','kategori_menu_id','nama','harga','stok_fisik',
            'aktif','deskripsi','foto','foto_lama',
            'ambang_stok_rendah','batas_stok_rendah'
        ]);
        $this->ambang_stok_rendah = 5;
        $this->batas_stok_rendah = 5;
        $this->aktif = true;
        $this->modal = true;
    }

    public function bukaEdit(int $id)
    {
        $m = Menu::findOrFail($id);
        $this->editId = $m->id;
        $this->kategori_menu_id = $m->kategori_menu_id;
        $this->nama = $m->nama;
        $this->harga = (int)$m->harga;
        $this->stok_fisik = (int)$m->stok_fisik;
        $this->aktif = (bool)$m->aktif;
        $this->deskripsi = $m->deskripsi;
        $this->foto_lama = $m->path_foto;
        $this->ambang_stok_rendah = (int)$m->ambang_stok_rendah;
        $this->batas_stok_rendah = (int)$m->batas_stok_rendah;
        $this->modal = true;
    }

    public function simpan()
    {
         $data = $this->validate([
            'kategori_menu_id' => 'required|exists:kategori_menu,id',
            'nama' => 'required|string|max:100',
            'harga' => 'required|integer|min:0',
            'stok_fisik' => 'required|integer|min:0',
            'aktif' => 'boolean',
            'deskripsi' => 'nullable|string|max:500',
            'foto' => 'nullable|image|max:2048',
        ]);

        $payload = [
            'kategori_menu_id' => $this->kategori_menu_id,
            'nama' => $this->nama,
            'harga' => (int)$this->harga,
            'stok_fisik' => (int)$this->stok_fisik,
            'aktif' => (bool)$this->aktif,
            'deskripsi' => $this->deskripsi,
            'ambang_stok_rendah' => (int)$this->ambang_stok_rendah,
            'batas_stok_rendah' => (int)$this->batas_stok_rendah,
        ];

        if ($this->foto) {
            $path = $this->foto->store('menu', 'public');
            $data['path_foto'] = $path;

            if ($this->foto_lama) {
                Storage::disk('public')->delete($this->foto_lama);
            }
        }

        unset($data['foto']);

        $this->editId
            ? Menu::whereKey($this->editId)->update($data)
            : Menu::create($data);

        $this->modal = false;
        session()->flash('pesan_sukses', 'Menu berhasil disimpan.');
    }


    public function hapus(int $id)
    {
        $m = Menu::findOrFail($id);
        if ($m->path_foto) Storage::disk('public')->delete($m->path_foto);
        $m->delete();
        session()->flash('pesan_sukses', 'Menu berhasil dihapus.');
    }

    public function bukaModalKategori(): void
    {
        $this->reset(['kategori_nama', 'kategori_aktif']);
        $this->kategori_aktif = true;
        $this->modalKategori = true;
    }

    public function simpanKategori(): void
    {
        $data = $this->validate([
            'kategori_nama' => 'required|string|max:50',
            'kategori_aktif' => 'boolean',
        ]);

        $kategori = \App\Models\KategoriMenu::create([
            'nama' => $data['kategori_nama'],
            'aktif' => $data['kategori_aktif'],
        ]);

        // otomatis pilih kategori baru untuk menu yang sedang dibuat
        $this->kategori_menu_id = $kategori->id;

        $this->modalKategori = false;
        session()->flash('pesan_sukses', 'Kategori berhasil ditambahkan.');
    }

    public function render()
    {
        $kategori = KategoriMenu::orderBy('nama')->get();

        $daftar = Menu::query()
            ->with('kategori')
            ->when($this->cari !== '', fn($q) => $q->where('nama','like','%'.$this->cari.'%'))
            ->when($this->filterKategori, fn($q) => $q->where('kategori_menu_id', $this->filterKategori))
            ->orderBy('nama')
            ->paginate(12);

        return view('livewire.admin.menu-crud', compact('daftar','kategori'));
    }
}
