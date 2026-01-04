<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Meja;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class MejaCrud extends Component
{
    use WithPagination, WithFileUploads;

    public string $cari = '';

    // form tambah/edit
    public bool $modal = false;
    public ?int $editId = null;
    public string $nama = '';
    public bool $aktif = true;

    // generate batch
    public int $jumlah = 5;
    public int $mulai = 1;
    public string $prefix = 'Meja ';
    public ?string $lokasi = null;
    public int $digit = 2;

    public function updatingCari() { $this->resetPage(); }

    public function bukaTambah()
    {
        $this->reset(['editId','nama','aktif','lokasi']);
        $this->aktif = true;
        $this->modal = true;
    }

    public function bukaEdit(int $id)
    {
        $m = Meja::findOrFail($id);
        $this->editId = $m->id;
        $this->nama = $m->nama;
        $this->lokasi = $m->lokasi;
        $this->aktif = (bool)$m->aktif;
        $this->modal = true;
    }

    public function simpan()
    {
        $data = $this->validate([
            'nama' => 'required|string|max:50',
            'lokasi' => 'nullable|string|max:100',
            'aktif' => 'boolean',
        ]);

        if ($this->editId) {
            Meja::whereKey($this->editId)->update($data);
        } else {
            $data['token_qr'] = $this->tokenUnik();
            Meja::create($data);
        }

        $this->modal = false;
        session()->flash('pesan_sukses', 'Meja berhasil disimpan.');
    }

    public function hapus(int $id)
    {
        Meja::whereKey($id)->delete();
        session()->flash('pesan_sukses', 'Meja berhasil dihapus.');
    }

    public function toggleAktif(int $id)
    {
        $m = Meja::findOrFail($id);
        $m->update(['aktif' => !$m->aktif]);
    }

    public function regenerasiToken(int $id)
    {
        $m = Meja::findOrFail($id);
        $m->update(['token_qr' => $this->tokenUnik()]);
        session()->flash('pesan_sukses', 'Token/QR meja berhasil diganti.');
    }

    public function generateBatch()
    {
        $this->validate([
            'jumlah' => 'required|integer|min:1|max:200',
            'mulai' => 'required|integer|min:1|max:9999',
            'digit' => 'required|integer|min:1|max:4',
            'prefix' => 'required|string|max:20',
        ]);

        for ($i=0; $i<$this->jumlah; $i++) {
            $no = str_pad((string)($this->mulai + $i), $this->digit, '0', STR_PAD_LEFT);
            Meja::create([
                'nama' => $this->prefix.$no,
                'aktif' => true,
                'token_qr' => $this->tokenUnik(),
            ]);
        }

        session()->flash('pesan_sukses', 'Berhasil generate '.$this->jumlah.' meja.');
    }

    private function tokenUnik(): string
    {
        do {
            $t = Str::random(32);
        } while (Meja::where('token_qr', $t)->exists());

        return $t;
    }

    public function render()
    {
        $daftar = Meja::query()
            ->when($this->cari !== '', fn($q) => $q->where('nama','like','%'.$this->cari.'%'))
            ->orderBy('nama')
            ->paginate(12);

        return view('livewire.admin.meja-crud', compact('daftar'));
    }
}
