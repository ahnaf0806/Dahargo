<div class="relative space-y-6 text-slate-100">
    {{-- background glow --}}
    <div class="pointer-events-none fixed inset-0 -z-10 bg-slate-950">
        <div class="absolute -top-28 left-1/2 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute -bottom-44 -right-40 h-[520px] w-[520px] rounded-full bg-cyan-400/10 blur-3xl"></div>
    </div>

    <div class="rounded-2xl border border-slate-700/50 bg-slate-900/35 p-6 shadow-[0_20px_80px_rgba(0,0,0,0.35)] backdrop-blur">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Checkout</h1>
            <a href="{{ route('pelanggan.menu') }}" class="text-sm font-semibold text-slate-300 hover:text-white hover:underline">
                Kembali
            </a>
        </div>

        @error('keranjang')
            <div class="mt-4 rounded-2xl border border-rose-500/25 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
                {{ $message }}
            </div>
        @enderror

        <div class="mt-5 space-y-3">
            @foreach($daftar as $item)
                <div class="flex items-center justify-between rounded-2xl border border-slate-700/50 bg-slate-950/30 p-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-100">{{ $item['nama'] }}</p>
                        <p class="text-xs text-slate-400">
                            Rp {{ number_format($item['harga'], 0, ',', '.') }} × {{ $item['jumlah'] }}
                        </p>
                    </div>
                    <p class="text-sm font-semibold text-slate-100">
                        Rp {{ number_format($item['total_baris'], 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-5 rounded-2xl border border-slate-700/50 bg-slate-950/30 p-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-300">Subtotal</p>
                <p class="text-sm font-semibold text-slate-100">
                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-700/50 bg-slate-900/35 p-6 shadow-[0_20px_80px_rgba(0,0,0,0.35)] backdrop-blur">
        <h2 class="text-base font-semibold">Metode Pembayaran</h2>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-700/50 bg-slate-950/30 p-4 hover:bg-slate-900/40">
                <input type="radio" class="h-4 w-4 accent-blue-500" wire:model.live="metode_pembayaran" value="tunai">
                <div>
                    <p class="text-sm font-semibold text-slate-100">Tunai</p>
                    <p class="text-xs text-slate-400">Bayar ke kasir/pelayan, admin akan validasi.</p>
                </div>
            </label>

            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-700/50 bg-slate-950/30 p-4 hover:bg-slate-900/40">
                <input type="radio" class="h-4 w-4 accent-blue-500" wire:model.live="metode_pembayaran" value="transfer">
                <div>
                    <p class="text-sm font-semibold text-slate-100">Transfer</p>
                    <p class="text-xs text-slate-400">Upload bukti, admin akan verifikasi.</p>
                </div>
            </label>
        </div>

        <div class="mt-5 space-y-4">
            <div>
                <label class="text-sm font-medium text-slate-200">Catatan (opsional)</label>
                <textarea
                    wire:model.defer="catatan_pelanggan"
                    class="mt-2 w-full rounded-2xl border border-slate-700/60 bg-slate-950/40 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20"
                    rows="3"
                    placeholder="Contoh: jangan pedas, tanpa es, dll"
                ></textarea>
                @error('catatan_pelanggan') <p class="mt-1 text-xs text-rose-200">{{ $message }}</p> @enderror
            </div>

            <div x-data x-show="$wire.metode_pembayaran === 'transfer'" x-cloak class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-slate-200">No Referensi (opsional)</label>
                    <input
                        wire:model.defer="no_referensi"
                        class="mt-2 w-full rounded-2xl border border-slate-700/60 bg-slate-950/40 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20"
                        placeholder="Contoh: TRX123456"
                    />
                    @error('no_referensi') <p class="mt-1 text-xs text-rose-200">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-200">Bukti Transfer</label>
                    <input
                        type="file"
                        wire:model="bukti_transfer"
                        class="mt-2 block w-full text-sm text-slate-200
                               file:mr-4 file:rounded-xl file:border-0
                               file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white
                               hover:file:bg-blue-500"
                    />
                    @error('bukti_transfer') <p class="mt-1 text-xs text-rose-200">{{ $message }}</p> @enderror

                    <div wire:loading wire:target="bukti_transfer" class="mt-2 text-xs text-slate-400">
                        Uploading...
                    </div>
                </div>
            </div>
        </div>

        <button
            wire:click="buatPesanan"
            wire:loading.attr="disabled"
            class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white
                   shadow-[0_18px_50px_rgba(37,99,235,0.32)]
                   hover:bg-blue-500 disabled:cursor-not-allowed disabled:bg-slate-700"
        >
            <span wire:loading.remove wire:target="buatPesanan">Buat Pesanan</span>
            <span wire:loading wire:target="buatPesanan">Memproses...</span>
        </button>
    </div>
</div>
