<div class="space-y-4">
    @if(session('pesan_sukses'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('pesan_sukses') }}
        </div>
    @endif

    <div class="rounded-lg bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="text-lg font-semibold text-gray-800">Daftar Menu</div>
                <div class="text-sm text-gray-500">Kelola menu, stok, dan status aktif.</div>
            </div>
            <button wire:click="bukaTambah"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                + Tambah Menu
            </button>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-3">
            <input wire:model.live="cari" class="rounded-md border-gray-300 text-sm" placeholder="Cari menu...">

            <select wire:model.live="filterKategori" class="rounded-md border-gray-300 text-sm">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="rounded-lg bg-white shadow-sm overflow-hidden">
        <div class="bg-slate-800 px-4 py-3 text-white font-semibold">List Menu</div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Gambar</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-right">Harga</th>
                    <th class="px-4 py-3 text-right">Stok</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
                </thead>

                <tbody class="divide-y">
                @forelse($daftar as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <img class="h-12 w-12 rounded-md object-cover border"
                                 src="{{ $m->path_foto ? asset('storage/'.$m->path_foto) : 'https://via.placeholder.com/80' }}">
                        </td>
                        <td class="px-4 py-3 font-semibold">{{ $m->nama }}</td>
                        <td class="px-4 py-3">{{ $m->kategori->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($m->harga,0,',','.') }}</td>
                        <td class="px-4 py-3 text-right">{{ $m->stok_fisik }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $m->aktif ? 'bg-green-100 text-green-700':'bg-gray-100 text-gray-700' }}">
                                {{ $m->aktif ? 'aktif' : 'nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <button wire:click="bukaEdit({{ $m->id }})"
                                        class="rounded-md border border-gray-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-gray-50">
                                    Edit
                                </button>
                                <button wire:click="hapus({{ $m->id }})"
                                        class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-gray-500">Belum ada menu.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">{{ $daftar->links() }}</div>
    </div>

    {{-- Modal --}}
    @if($modal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="w-full max-w-2xl rounded-lg bg-white p-5 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="text-lg font-bold">{{ $editId ? 'Edit Menu' : 'Tambah Menu' }}</div>
                    <button type="button"
                            wire:click="bukaModalKategori"
                            class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                        + Tambah Kategori
                    </button>
                    <button wire:click="$set('modal', false)" class="text-gray-500 hover:text-gray-900">✕</button>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Kategori</label>
                        <select wire:model="kategori_menu_id" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                            <option value="">Pilih kategori</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_menu_id') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama</label>
                        <input wire:model="nama" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        @error('nama') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Harga</label>
                        <input wire:model="harga" type="number" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        @error('harga') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700">Stok Fisik</label>
                        <input wire:model="stok_fisik" type="number" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        @error('stok_fisik') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea wire:model="deskripsi" rows="3" class="mt-1 w-full rounded-md border-gray-300 text-sm"></textarea>
                        @error('deskripsi') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Foto</label>
                        <input wire:model="foto" type="file" accept="image/*" class="mt-1 w-full text-sm">
                        @error('foto') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <label class="md:col-span-2 inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="aktif" class="rounded border-gray-300">
                        Aktif
                    </label>
                </div>

                <div class="mt-5 flex justify-end gap-2">
                    <button wire:click="$set('modal', false)"
                            class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-gray-50">
                        Batal
                    </button>
                    <button wire:click="simpan"
                            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif
    @if($modalKategori)
    <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-5 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="text-lg font-bold">Tambah Kategori</div>
                <button wire:click="$set('modalKategori', false)" class="text-gray-500 hover:text-gray-900">✕</button>
            </div>

            <div class="mt-4 space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input wire:model="kategori_nama" class="mt-1 w-full rounded-md border-gray-300 text-sm" placeholder="Contoh: Makanan">
                    @error('kategori_nama') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>

                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="kategori_aktif" class="rounded border-gray-300">
                    Aktif
                </label>
            </div>

            <div class="mt-5 flex justify-end gap-2">
                <button wire:click="$set('modalKategori', false)"
                        class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-gray-50">
                    Batal
                </button>
                <button wire:click="simpanKategori"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </div>
    </div>
@endif
</div>
