<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">Detail Pesanan</h2>

        <a href="{{ route('admin.pesanan.index') }}"
           class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium hover:bg-gray-50">
            Kembali
        </a>
    </div>
</x-slot>

<div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-4">

        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500">Kode</p>
                    <p class="text-lg font-semibold">{{ $pesanan->kode }}</p>
                    <p class="mt-1 text-sm text-gray-600">
                        Meja: <b>{{ $pesanan->meja->nama ?? '-' }}</b>
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $pesanan->metode_pembayaran }} • {{ $pesanan->status_pembayaran }} • {{ $pesanan->status }}
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-lg font-bold">Rp {{ number_format($pesanan->total,0,',','.') }}</p>
                </div>
            </div>

            @if($pesanan->pembayaran?->path_bukti)
                <div class="mt-4">
                    <p class="text-sm font-medium">Bukti Transfer</p>
                    <a class="text-sm text-blue-600 underline"
                       href="{{ asset('storage/'.$pesanan->pembayaran->path_bukti) }}" target="_blank" rel="noopener">
                        Lihat Bukti
                    </a>
                </div>
            @endif

            @if(filled($pesanan->catatan_pelanggan))
                <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                    <p class="text-sm font-semibold text-yellow-800">Catatan Pelanggan</p>
                    <p class="mt-1 text-sm text-yellow-900 whitespace-pre-line">
                        {{ $pesanan->catatan_pelanggan }}
                    </p>
                </div>
            @endif
        </div>

        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold">Item</h3>

            <div class="mt-4 space-y-3">
                @foreach($pesanan->item as $it)
                    <div class="flex items-start justify-between gap-4 rounded-lg border p-4">
                        <div>
                            <p class="text-sm font-semibold">{{ $it->nama_menu_snapshot }}</p>
                            <p class="text-xs text-gray-500">
                                Rp {{ number_format($it->harga_snapshot,0,',','.') }} × {{ $it->jumlah }}
                            </p>

                            @if(filled($it->catatan))
                                <p class="mt-1 text-xs text-gray-600 whitespace-pre-line">
                                    <b>Catatan item:</b> {{ $it->catatan }}
                                </p>
                            @endif
                        </div>

                        <p class="text-sm font-semibold">
                            Rp {{ number_format($it->total_baris,0,',','.') }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="mt-5 flex flex-col gap-2 sm:flex-row relative z-10">
                <button type="button"
                    wire:click.prevent.stop="verifikasiDanProses"
                    wire:loading.attr="disabled"
                    wire:target="verifikasiDanProses"
                    class="relative z-10 inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 disabled:opacity-60">
                    <span wire:loading.remove wire:target="verifikasiDanProses">Verifikasi / Tandai Lunas</span>
                    <span wire:loading wire:target="verifikasiDanProses">Memproses...</span>
                </button>

                <button type="button"
                    wire:click.prevent.stop="tolakPembayaran"
                    wire:loading.attr="disabled"
                    wire:target="tolakPembayaran"
                    class="relative z-10 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium hover:bg-gray-50 disabled:opacity-60">
                    <span wire:loading.remove wire:target="tolakPembayaran">Tolak / Batalkan</span>
                    <span wire:loading wire:target="tolakPembayaran">Memproses...</span>
                </button>
            </div>

            <p class="mt-3 text-xs text-gray-500">
                Tombol “Verifikasi” akan <b>komit stok</b> (stok_fisik turun & stok_dipesan turun) + membuat mutasi_stok.
            </p>
        </div>
    </div>
</div>
