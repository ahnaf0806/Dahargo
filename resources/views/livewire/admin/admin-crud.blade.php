<div class="space-y-4">
  @if(session('pesan_sukses'))
    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
      {{ session('pesan_sukses') }}
    </div>
  @endif

  <div class="rounded-lg bg-white p-4 shadow-sm flex items-center justify-between gap-3">
    <div>
      <div class="text-lg font-semibold text-gray-800">Kelola Admin</div>
      <div class="text-sm text-gray-500">Kelola Kasir Disini</div>
    </div>
    <button wire:click="bukaTambah" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
      + Tambah Admin
    </button>
  </div>

  <div class="rounded-lg bg-white shadow-sm overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-800 px-4 py-3">
      <div class="font-semibold text-white">Daftar Admin</div>

      <div class="flex items-center gap-2">
        <input wire:model.live="cari" class="w-56 rounded-md border-0 bg-white/10 text-sm text-white placeholder-white/70 focus:ring-0" placeholder="Cari...">

        <select wire:model.live="filterAktif"
            class="rounded-md border-0 bg-white/10 text-sm text-white focus:ring-0">
            <option value="all" class="bg-white text-slate-900">Semua</option>
            <option value="aktif" class="bg-white text-slate-900">Akun Aktif</option>
            <option value="nonaktif" class="bg-white text-slate-900">Akun Nonaktif</option>
            </select>

            <select wire:model.live="filterOnline"
            class="rounded-md border-0 bg-white/10 text-sm text-white focus:ring-0">
            <option value="all" class="bg-white text-slate-900">Semua</option>
            <option value="online" class="bg-white text-slate-900">Online</option>
            <option value="offline" class="bg-white text-slate-900">Offline</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
          <tr>
            <th class="px-4 py-3 text-left">Nama</th>
            <th class="px-4 py-3 text-left">Email</th>
            <th class="px-4 py-3 text-left">Role</th>
            <th class="px-4 py-3 text-left">Akun</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Login Terakhir</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @foreach($admins as $u)
            @php
              $online = in_array($u->id, $onlineIds, true);
            @endphp
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 font-semibold">{{ $u->name }}</td>
              <td class="px-4 py-3">{{ $u->email }}</td>
              <td class="px-4 py-3">
                <span class="rounded-full px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-700">
                  {{ $u->role ?? 'admin' }}
                </span>
              </td>
              <td class="px-4 py-3">
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $u->is_active ? 'bg-green-100 text-green-700':'bg-gray-100 text-gray-700' }}">
                  {{ $u->is_active ? 'aktif' : 'nonaktif' }}
                </span>
              </td>
              <td class="px-4 py-3">
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $online ? 'bg-blue-100 text-blue-700':'bg-gray-100 text-gray-700' }}">
                  {{ $online ? 'online' : 'offline' }}
                </span>
              </td>
              <td class="px-4 py-3 text-gray-700">
                {{ optional($u->last_login_at)->format('d M Y H:i') ?? '-' }}
              </td>
              <td class="px-4 py-3">
                <div class="flex justify-center gap-2">
                  <button wire:click="bukaEdit({{ $u->id }})" class="rounded-md border border-gray-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-gray-50">Edit</button>
                  <button wire:click="toggleAktif({{ $u->id }})" class="rounded-md border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100">Toggle</button>
                  <button wire:click="hapus({{ $u->id }})" class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">Hapus</button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="p-4">{{ $admins->links() }}</div>
  </div>

  @if($modal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
      <div class="w-full max-w-lg rounded-lg bg-white p-5 shadow-lg">
        <div class="flex items-center justify-between">
          <div class="text-lg font-bold">{{ $editId ? 'Edit Admin' : 'Tambah Admin' }}</div>
          <button wire:click="$set('modal', false)" class="text-gray-500 hover:text-gray-900">✕</button>
        </div>

        <div class="mt-4 space-y-3">
          <div>
            <label class="text-sm font-medium text-gray-700">Nama</label>
            <input wire:model="name" class="mt-1 w-full rounded-md border-gray-300 text-sm">
            @error('name') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
          </div>

          <div>
            <label class="text-sm font-medium text-gray-700">Email</label>
            <input wire:model="email" class="mt-1 w-full rounded-md border-gray-300 text-sm">
            @error('email') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
          </div>

          <div>
            <label class="text-sm font-medium text-gray-700">
              Password {{ $editId ? '(kosongkan jika tidak diganti)' : '' }}
            </label>
            <input wire:model="password" type="password" class="mt-1 w-full rounded-md border-gray-300 text-sm">
            @error('password') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
          </div>

          <div class="flex items-center gap-3">
            <div class="flex-1">
              <label class="text-sm font-medium text-gray-700">Role</label>
              <select wire:model="role" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                <option value="admin">admin</option>
                <option value="superadmin">superadmin</option>
              </select>
              @error('role') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <label class="inline-flex items-center gap-2 text-sm mt-6">
              <input type="checkbox" wire:model="is_active" class="rounded border-gray-300">
              Aktif
            </label>
          </div>
        </div>

        <div class="mt-5 flex justify-end gap-2">
          <button wire:click="$set('modal', false)" class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-gray-50">Batal</button>
          <button wire:click="simpan" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Simpan</button>
        </div>
      </div>
    </div>
  @endif
</div>
