<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.admin-layout')]
class AdminCrud extends Component
{
    use WithPagination;

    public string $cari = '';
    public string $filterAktif = 'all'; // all|aktif|nonaktif
    public string $filterOnline = 'all'; // all|online|offline

    public bool $modal = false;
    public ?int $editId = null;

    public string $name = '';
    public string $email = '';
    public string $role = 'admin';
    public bool $is_active = true;
    public string $password = '';

    public function updatingCari() { $this->resetPage(); }

    public function bukaTambah()
    {
        $this->reset(['editId','name','email','role','is_active','password']);
        $this->role = 'admin';
        $this->is_active = true;
        $this->modal = true;
    }

    public function bukaEdit(int $id)
    {
        $u = User::findOrFail($id);
        $this->editId = $u->id;
        $this->name = $u->name;
        $this->email = $u->email;
        $this->role = $u->role ?? 'admin';
        $this->is_active = (bool) $u->is_active;
        $this->password = '';
        $this->modal = true;
    }

    public function simpan()
    {
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,'.($this->editId ?? 'NULL').',id',
            'role' => 'required|in:admin,superadmin',
            'is_active' => 'boolean',
        ];
        if ($this->editId) {
            if ($this->password !== '') $rules['password'] = 'min:8';
        } else {
            $rules['password'] = 'required|min:8';
        }

        $data = $this->validate($rules);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        User::updateOrCreate(['id' => $this->editId], $data);

        $this->dispatch('notif', type: 'success', message: 'Berhasil Disimpan!!');
    }

    public function toggleAktif(int $id)
    {
        if (auth()->id() === $id) return; // cegah matiin akun sendiri
        $u = User::findOrFail($id);
        $u->update(['is_active' => ! $u->is_active]);
    }

    public function hapus(int $id)
    {
        if (auth()->id() === $id) return; // cegah hapus diri sendiri
        User::whereKey($id)->delete();
        session()->flash('pesan_sukses', 'Admin berhasil dihapus.');
    }

    public function render()
    {
        $base = User::query();

        // kalau users kamu khusus admin semua, ini opsional:
        $base->whereIn('role', ['admin','superadmin']);

        $base->when($this->cari !== '', fn($q) =>
            $q->where(fn($qq) =>
                $qq->where('name','like',"%{$this->cari}%")
                   ->orWhere('email','like',"%{$this->cari}%")
            )
        );

        if ($this->filterAktif === 'aktif') $base->where('is_active', true);
        if ($this->filterAktif === 'nonaktif') $base->where('is_active', false);

        $admins = $base->orderBy('name')->paginate(12);

        // ambil last_activity dari tabel sessions (database session driver)
        $ids = $admins->pluck('id');
        $activity = DB::table('sessions')
            ->select('user_id', DB::raw('MAX(last_activity) as last_activity'))
            ->whereIn('user_id', $ids)
            ->groupBy('user_id')
            ->pluck('last_activity', 'user_id');

        $onlineCutoff = now()->subMinutes(10)->timestamp;
        $onlineIds = $activity->filter(fn($ts) => (int)$ts >= $onlineCutoff)->keys()->all();

        // filter online/offline di level list (simple)
        if ($this->filterOnline !== 'all') {
            $admins->setCollection(
                $admins->getCollection()->filter(function ($u) use ($onlineIds) {
                    $isOnline = in_array($u->id, $onlineIds, true);
                    return $this->filterOnline === 'online' ? $isOnline : ! $isOnline;
                })->values()
            );
        }

        return view('livewire.admin.admin-crud', [
            'admins' => $admins,
            'onlineIds' => $onlineIds,
            'lastActivity' => $activity,
        ]);
    }
}