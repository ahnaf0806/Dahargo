<div class="space-y-4">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-800/70 bg-slate-950/60 p-6 shadow-[0_20px_60px_rgba(0,0,0,0.45)] backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-100">Riwayat Pesanan</h1>
                <p class="text-sm text-slate-400">Riwayat otomatis tersimpan di perangkat ini.</p>
            </div>

            <a href="{{ route('pelanggan.menu') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-700/70 bg-slate-900/40 px-4 py-2 text-sm font-semibold text-slate-100 hover:bg-slate-900/70 transition">
                Kembali ke Menu
            </a>
        </div>
    </div>

    {{-- LIST --}}
    <div class="rounded-2xl border border-slate-800/70 bg-slate-950/60 shadow-[0_20px_60px_rgba(0,0,0,0.45)] backdrop-blur overflow-hidden">
        @if($pesanan->isEmpty())
            <div class="p-6 text-sm text-slate-400">
                Belum ada pesanan.
            </div>
        @else
            <div class="divide-y divide-slate-800/70">
                @foreach($pesanan as $p)
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            {{-- KIRI --}}
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-100">
                                    <span class="font-mono">{{ $p->kode }}</span>
                                    <span class="text-slate-400">•</span>
                                    <span class="text-slate-200">{{ $p->meja->nama ?? '-' }}</span>
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $p->metode_pembayaran }}
                                    <span class="text-slate-600">•</span>
                                    {{ $p->status_pembayaran }}
                                    <span class="text-slate-600">•</span>
                                    {{ $p->status }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ optional($p->waktu_pesan)->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            {{-- KANAN --}}
                            <div class="text-left sm:text-right">
                                <p class="text-sm font-bold text-slate-100 whitespace-nowrap">
                                    Rp {{ number_format($p->total,0,',','.') }}
                                </p>

                                <div class="mt-3 flex flex-wrap gap-2 sm:justify-end">
                                    <a href="{{ route('pelanggan.pesanan.status', ['kode' => $p->kode]) }}"
                                       class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow-[0_14px_40px_rgba(37,99,235,0.35)] hover:bg-blue-500 active:scale-[0.99] transition">
                                        Lihat Status
                                    </a>

                                    @if($p->status_pembayaran === \App\Models\Pesanan::BAYAR_LUNAS)
                                        <a href="{{ url('/struk/'.$p->kode) }}"
                                           class="inline-flex items-center justify-center rounded-xl border border-slate-700/70 bg-slate-900/40 px-3 py-2 text-xs font-semibold text-slate-100 hover:bg-slate-900/70 transition">
                                            Struk
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-slate-800/70">
                {{-- pagination --}}
                <div class="[&_*]:text-slate-200 [&_span]:text-slate-400">
                    {{ $pesanan->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
