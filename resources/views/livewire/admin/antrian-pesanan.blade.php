
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Antrian Pesanan</h2>
            <livewire:admin.notifikasi-pesanan-masuk />
        </div>
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500">Auto refresh</span>
        </div>
    </x-slot>

    <div wire:poll.5s.visible="muat">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-xl border bg-white shadow-sm">
                <div class="p-4 border-b">
                    <p class="text-sm text-gray-600">Klik pesanan untuk verifikasi pembayaran & proses.</p>
                </div>

                <div class="divide-y">
                    @foreach($pesanan as $p)
                        <div wire:key="pesanan-{{ $p->id }}" class="p-4 hover:bg-gray-50">
                        <div class="flex items-start justify-between gap-4">
                            {{-- AREA KLIK DETAIL (hanya bagian kiri) --}}
                            <a href="{{ route('admin.pesanan.detail', $p->id) }}" class="block flex-1">
                            <p class="text-sm font-semibold">
                                {{ $p->kode }} • {{ $p->meja->nama ?? '-' }}
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $p->metode_pembayaran }} • {{ $p->status_pembayaran }} • {{ $p->status }}
                            </p>

                            @if(filled($p->catatan_pelanggan))
                                <p class="mt-1 text-xs text-yellow-700">
                                Catatan: {{ \Illuminate\Support\Str::limit($p->catatan_pelanggan, 40) }}
                                </p>
                            @endif
                            </a>

                            {{-- TOTAL + TOMBOL AKSI (tidak membuka detail) --}}
                            @php
                                $bayar = $p->status_pembayaran;
                                $isLunas = $bayar === \App\Models\Pesanan::BAYAR_LUNAS;
                                $perluKonfirmasi = in_array($bayar, [
                                    \App\Models\Pesanan::BAYAR_BELUM,
                                    \App\Models\Pesanan::BAYAR_MENUNGGU_VERIF,
                                    \App\Models\Pesanan::BAYAR_DITOLAK,
                                ], true);
                            @endphp

                                <div class="mt-2 flex flex-wrap justify-end gap-2">
                                @if($perluKonfirmasi && !$isLunas)
                                    {{-- STEP 1: KONFIRMASI --}}
                                    <button type="button"
                                    wire:click="setVerifikasi({{ $p->id }}, '{{ \App\Models\Pesanan::BAYAR_LUNAS }}')"
                                    class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                    Terima
                                    </button>

                                    <button type="button"
                                    wire:click="setVerifikasi({{ $p->id }}, '{{ \App\Models\Pesanan::BAYAR_MENUNGGU_VERIF }}')"
                                    class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-amber-600">
                                    Pending
                                    </button>

                                    <button type="button"
                                    wire:click="setVerifikasi({{ $p->id }}, '{{ \App\Models\Pesanan::BAYAR_DITOLAK }}')"
                                    class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                                    Tolak
                                    </button>

                                @elseif($isLunas)
                                    {{-- STEP 2: UPDATE STATUS PESANAN --}}
                                    <button type="button"
                                    wire:click="setStatusPesanan({{ $p->id }}, '{{ \App\Models\Pesanan::STATUS_DIPROSES }}')"
                                    class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">
                                    Sedang dibuat
                                    </button>

                                    <button type="button"
                                    wire:click="setStatusPesanan({{ $p->id }}, '{{ \App\Models\Pesanan::STATUS_SELESAI }}')"
                                    class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">
                                    Selesai
                                    </button>

                                    {{-- CETAK STRUK MUNCUL HANYA JIKA DITERIMA --}}
                                    <a
                                    href="{{ route('admin.pesanan.struk', $p->id) }}"
                                    target="_blank" rel="noopener"
                                    class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold hover:bg-slate-50">
                                    Cetak Struk
                                    </a>
                                @endif
                                </div>

                        </div>
                        </div>
                    @endforeach
                    </div>


                <div class="p-4">
                    {{ $pesanan->links() }}
                </div>
            </div>
        </div>
    </div>
