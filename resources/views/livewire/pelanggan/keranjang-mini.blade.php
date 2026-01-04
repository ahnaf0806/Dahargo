<div class="space-y-4 text-slate-100">
    @if($jumlahItem <= 0)
        <div class="rounded-2xl border border-dashed border-white/15 bg-white/5 p-4 text-sm text-slate-300">
            Keranjang masih kosong.
        </div>
    @else
        <div class="space-y-3">
            @foreach($daftar as $item)
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-white">
                                {{ $item['nama'] }}
                            </p>
                            <p class="mt-1 text-xs text-slate-300">
                                Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                <span class="mx-1 text-white/20">•</span>
                                Stok: {{ $item['stok_tersedia'] }}
                            </p>
                        </div>

                        <button
                            wire:click="hapus({{ $item['id'] }})"
                            class="shrink-0 rounded-xl border border-white/15 bg-white/5 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-500/15 hover:border-red-400/30"
                        >
                            Hapus
                        </button>
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-3">
                        <div class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-black/20 p-1">
                            <button
                                wire:click="kurang({{ $item['id'] }})"
                                class="grid h-9 w-9 place-items-center rounded-xl border border-white/10 bg-white/5 text-sm text-white transition hover:bg-white/10"
                            >-</button>

                            <span class="w-9 text-center text-sm font-semibold text-white">
                                {{ $item['jumlah'] }}
                            </span>

                            <button
                                wire:click="tambah({{ $item['id'] }})"
                                class="grid h-9 w-9 place-items-center rounded-xl border border-white/10 bg-white/5 text-sm text-white transition hover:bg-white/10"
                            >+</button>
                        </div>

                        <p class="text-right text-sm font-semibold text-white">
                            Rp {{ number_format($item['total_baris'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-300">Subtotal</p>
                <p class="text-sm font-semibold text-white">
                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                </p>
            </div>

            <div class="mt-4 flex flex-col gap-2">
                <a
                    href="{{ url('/checkout') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-[0_14px_40px_rgba(37,99,235,0.25)] transition hover:bg-blue-500"
                >
                    Checkout
                </a>

                <button
                    wire:click="kosongkan"
                    class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10"
                >
                    Kosongkan Keranjang
                </button>
            </div>
        </div>
    @endif
</div>
