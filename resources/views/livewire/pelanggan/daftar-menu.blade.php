<div class="relative space-y-6 text-slate-100">

   
    <div class="pointer-events-none fixed inset-0 -z-10 bg-slate-950">
        <div class="absolute -top-40 left-1/2 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute -bottom-56 -right-56 h-[520px] w-[520px] rounded-full bg-sky-400/15 blur-3xl"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-950 to-black"></div>
    </div>

    {{-- HEADER --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight">Daftar Menu</h1>
            <p class="mt-1 text-sm text-slate-300">Pilih menu, lalu checkout tanpa login.</p>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="rounded-2xl bg-white/5 p-4 shadow-[0_20px_70px_rgba(0,0,0,0.35)] ring-1 ring-white/10 backdrop-blur">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap gap-2">
                <button
                    wire:click="pilihKategori(0)"
                    class="rounded-full px-3 py-1.5 text-sm ring-1 transition"
                    @class([
                        'bg-blue-600 text-white ring-blue-500/40 shadow-[0_10px_30px_rgba(37,99,235,0.25)]' => $kategori_id === 0,
                        'bg-white/5 text-slate-200 ring-white/10 hover:bg-white/10 hover:text-white' => $kategori_id !== 0,
                    ])
                >
                    Semua
                </button>

                @foreach($kategori as $k)
                    <button
                        wire:click="pilihKategori({{ $k->id }})"
                        class="rounded-full px-3 py-1.5 text-sm ring-1 transition"
                        @class([
                            'bg-blue-600 text-white ring-blue-500/40 shadow-[0_10px_30px_rgba(37,99,235,0.25)]' => $kategori_id === $k->id,
                            'bg-white/5 text-slate-200 ring-white/10 hover:bg-white/10 hover:text-white' => $kategori_id !== $k->id,
                        ])
                    >
                        {{ $k->nama }}
                    </button>
                @endforeach
            </div>

            <div class="w-full sm:w-72">
                <input
                    wire:model.live="cari"
                    type="text"
                    placeholder="Cari menu..."
                    class="w-full rounded-xl border border-white/10 bg-slate-900/60 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-400 outline-none
                           focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/30"
                />
            </div>
        </div>
    </div>

    {{-- GRID MENU --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($menu as $m)
            <div class="group overflow-hidden rounded-2xl bg-white/5 ring-1 ring-white/10 backdrop-blur
                        shadow-[0_18px_60px_rgba(0,0,0,0.35)] transition
                        hover:-translate-y-0.5 hover:ring-blue-500/30">

                {{-- FOTO MENU --}}
                <div class="relative h-56 overflow-hidden">
                    <img
                        src="{{ $m->path_foto ? asset('storage/'.$m->path_foto) : 'https://via.placeholder.com/400x300?text=Menu' }}"
                        alt="{{ $m->nama }}"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.04]"
                    />
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/50 via-black/0 to-black/0"></div>
                </div>

                {{-- ISI CARD --}}
                <div class="flex flex-1 flex-col gap-2 p-4">
                    {{-- NAMA MENU --}}
                    <p class="text-base font-semibold text-white">
                        {{ $m->nama }}
                    </p>

                    {{-- DESKRIPSI --}}
                    @if($m->deskripsi)
                        <p class="text-sm text-slate-300 line-clamp-2">
                            {{ $m->deskripsi }}
                        </p>
                    @endif

                    {{-- STOK --}}
                    @if($m->stok_tersedia <= 0)
                        <span class="mt-1 w-fit rounded-full bg-red-500/10 px-2.5 py-1 text-xs font-medium text-red-200 ring-1 ring-red-500/20">
                            Stok habis
                        </span>
                    @else
                        <span class="mt-1 w-fit rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-200 ring-1 ring-emerald-500/20">
                            Tersedia: {{ $m->stok_tersedia }}
                        </span>
                    @endif

                    {{-- FOOTER --}}
                    <div class="mt-auto flex items-center justify-between pt-3">
                        <p class="text-sm font-semibold text-white">
                            Rp {{ number_format($m->harga, 0, ',', '.') }}
                        </p>

                        <button
                            wire:click="tambahKeKeranjang({{ $m->id }})"
                            @disabled($m->stok_tersedia <= 0)
                            class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition"
                            @class([
                                'bg-blue-600 text-white hover:bg-blue-500 shadow-[0_10px_30px_rgba(37,99,235,0.25)]' => $m->stok_tersedia > 0,
                                'bg-white/10 text-slate-400 cursor-not-allowed' => $m->stok_tersedia <= 0,
                            ])
                        >
                            Tambah
                        </button>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-span-full rounded-2xl bg-white/5 p-6 text-sm text-slate-300 ring-1 ring-white/10">
                Menu tidak ditemukan.
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="rounded-2xl bg-white/5 px-4 py-3 ring-1 ring-white/10 backdrop-blur">
        {{ $menu->links() }}
    </div>

    {{-- Drawer Keranjang --}}
    <div
        x-show="bukaKeranjang"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-40 bg-black/60"
        x-on:click="bukaKeranjang=false"
    ></div>

    <div
        x-show="bukaKeranjang"
        x-cloak
        x-transition
        class="fixed right-0 top-0 z-50 h-full w-[min(92vw,420px)] bg-slate-950 text-slate-100 shadow-[0_30px_120px_rgba(0,0,0,0.6)] ring-1 ring-white/10"
        x-on:keydown.escape.window="bukaKeranjang=false"
    >
        <div class="flex items-center justify-between border-b border-white/10 p-4">
            <p class="text-base font-semibold">Keranjang</p>
            <button
                type="button"
                x-on:click="bukaKeranjang=false"
                class="rounded-xl bg-white/5 px-3 py-1.5 text-sm ring-1 ring-white/10 hover:bg-white/10"
            >
                Tutup
            </button>
        </div>

        <div class="p-4">
            <livewire:pelanggan.keranjang-mini />
        </div>
    </div>
</div>
