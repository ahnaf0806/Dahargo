<div wire:poll.5s class="space-y-4">

    {{-- CARD: STATUS PESANAN --}}
    <div class="rounded-2xl border border-slate-800/70 bg-slate-950/60 p-6 shadow-[0_20px_60px_rgba(0,0,0,0.45)] backdrop-blur">
        <div class="flex flex-col gap-1">
            <h1 class="text-xl font-semibold text-slate-100">Status Pesanan</h1>
            <p class="text-sm text-slate-400">
                Kode:
                <span class="font-mono font-semibold text-slate-200">{{ $pesanan->kode }}</span>
            </p>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div class="rounded-xl border border-slate-800/60 bg-slate-900/30 p-4">
                <p class="text-sm text-slate-400">Status Pesanan</p>
                <p class="mt-1 text-base font-semibold text-slate-100">{{ $pesanan->status }}</p>
            </div>

            <div class="rounded-xl border border-slate-800/60 bg-slate-900/30 p-4">
                <p class="text-sm text-slate-400">Status Pembayaran</p>
                <p class="mt-1 text-base font-semibold text-slate-100">{{ $pesanan->status_pembayaran }}</p>
            </div>
        </div>

        {{-- TOTAL --}}
        <div class="mt-4 rounded-xl border border-blue-500/20 bg-gradient-to-r from-blue-600/15 via-slate-900/30 to-slate-900/30 p-4">
            <p class="text-sm text-slate-300">Total</p>
            <p class="mt-1 text-lg font-semibold text-slate-100">
                Rp {{ number_format($pesanan->total, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-slate-400">
                Metode: {{ $pesanan->metode_pembayaran }}
            </p>
        </div>

        @if($pesanan->status_pembayaran === 'lunas')
            <a href="{{ route('pelanggan.struk', ['kode' => $pesanan->kode]) }}"
               class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-[0_14px_40px_rgba(37,99,235,0.35)] hover:bg-blue-500 active:scale-[0.99] transition">
                Lihat Struk
            </a>
        @else
            <div class="mt-5 rounded-xl border border-yellow-400/30 bg-yellow-500/10 px-4 py-3 text-sm text-yellow-100">
                Menunggu validasi admin. Halaman ini akan otomatis update.
            </div>
        @endif
    </div>

    {{-- CARD: RINCIAN ITEM --}}
    <div class="rounded-2xl border border-slate-800/70 bg-slate-950/60 p-6 shadow-[0_20px_60px_rgba(0,0,0,0.45)] backdrop-blur">
        <h2 class="text-base font-semibold text-slate-100">Rincian Item</h2>

        <div class="mt-4 space-y-2">
            @foreach($pesanan->item as $it)
                <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-800/60 bg-slate-900/30 p-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-100 truncate">
                            {{ $it->nama_menu_snapshot }}
                        </p>
                        <p class="text-xs text-slate-400">
                            Rp {{ number_format($it->harga_snapshot, 0, ',', '.') }} × {{ $it->jumlah }}
                        </p>
                    </div>

                    <p class="text-sm font-semibold text-slate-100 whitespace-nowrap">
                        Rp {{ number_format($it->total_baris, 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

</div>
