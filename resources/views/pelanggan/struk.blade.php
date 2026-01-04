@extends('pelanggan.layouts.pelanggan', ['judul' => 'Struk'])

@section('konten')
<div class="space-y-4">
    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-[0_20px_70px_rgba(0,0,0,0.35)]
                print:rounded-none print:border-0 print:bg-white print:p-0 print:shadow-none">

        {{-- Actions --}}
        <div class="flex items-center justify-between gap-3 print:hidden">
            <a href="{{ route('pelanggan.riwayat') }}"
               class="rounded-xl border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white
                      transition hover:bg-white/10">
                Kembali
            </a>

            <button onclick="window.print()"
                    class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white
                           shadow-[0_14px_40px_rgba(37,99,235,0.25)] transition hover:bg-blue-500">
                Print
            </button>
        </div>

        {{-- Header --}}
        <div class="mt-4 text-center print:mt-0">
            <p class="text-lg font-extrabold tracking-wide text-white print:text-black">
                {{ config('app.name', 'Pemesanan Resto') }}
            </p>
            <p class="mt-1 text-sm text-slate-300 print:text-gray-600">Struk Pembelian</p>
        </div>

        {{-- Info --}}
        <div class="mt-5 rounded-2xl border border-white/10 bg-black/20 p-4 print:rounded-none print:border print:border-gray-200 print:bg-gray-50">
            <div class="grid grid-cols-1 gap-2 text-sm">
                <p class="text-slate-200 print:text-gray-700">
                    <span class="text-slate-400 print:text-gray-500">Kode:</span>
                    <span class="font-mono font-semibold text-white print:text-black">{{ $pesanan->kode }}</span>
                </p>
                <p class="text-slate-200 print:text-gray-700">
                    <span class="text-slate-400 print:text-gray-500">Meja:</span>
                    <span class="font-semibold text-white print:text-black">{{ $pesanan->meja->nama ?? '-' }}</span>
                </p>
                <p class="text-slate-200 print:text-gray-700">
                    <span class="text-slate-400 print:text-gray-500">Waktu:</span>
                    {{ optional($pesanan->waktu_validasi ?? $pesanan->waktu_pesan)->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        {{-- Items --}}
        <div class="mt-5">
            <div class="flex items-center justify-between border-b border-white/10 pb-2 print:border-gray-200">
                <p class="text-sm font-semibold text-white print:text-black">Rincian</p>
                <p class="text-xs text-slate-400 print:text-gray-500">Qty & Harga</p>
            </div>

            <div class="mt-3 space-y-3">
                @foreach($pesanan->item as $it)
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-white print:text-black">
                                {{ $it->nama_menu_snapshot }}
                            </p>
                            <p class="mt-0.5 text-xs text-slate-300 print:text-gray-600">
                                Rp {{ number_format($it->harga_snapshot,0,',','.') }} × {{ $it->jumlah }}
                            </p>
                        </div>
                        <p class="shrink-0 text-sm font-semibold text-white print:text-black">
                            Rp {{ number_format($it->total_baris,0,',','.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Total --}}
        <div class="mt-6 rounded-2xl border border-white/10 bg-black/20 p-4
                    print:rounded-none print:border print:border-gray-200 print:bg-gray-50">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-300 print:text-gray-700">Total</p>
                <p class="text-base font-extrabold text-white print:text-black">
                    Rp {{ number_format($pesanan->total,0,',','.') }}
                </p>
            </div>
            <p class="mt-1 text-xs text-slate-400 print:text-gray-600">
                Metode: <span class="font-semibold text-slate-200 print:text-gray-800">{{ $pesanan->metode_pembayaran }}</span>
            </p>
        </div>

        <div class="mt-6 text-center">
            <p class="text-xs text-slate-400 print:text-gray-500">
                Terima kasih 🙏
            </p>
        </div>
    </div>
</div>
@endsection
