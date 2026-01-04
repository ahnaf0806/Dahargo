<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $judul ?? config('app.name', 'Pemesanan Resto') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body x-data="{ bukaKeranjang: false }" class="min-h-screen bg-[#0b1220] text-slate-100">
    {{-- Background glow --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-36 left-1/2 h-[560px] w-[560px] -translate-x-1/2 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute -bottom-48 -right-48 h-[560px] w-[560px] rounded-full bg-indigo-500/15 blur-3xl"></div>
    </div>

    {{-- Header --}}
    <header class="mx-auto max-w-[825px] rounded-2xl border border-white/10 bg-slate-950/60 shadow-[0_20px_70px_rgba(0,0,0,0.55)] backdrop-blur">
        <div class="mx-auto max-w-4xl px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                {{-- Left --}}
                <div>
                    <p class="text-lg font-extrabold leading-tight tracking-tight text-white">
                        {{ config('app.name', 'Pemesanan Resto') }}
                    </p>
                    <p class="text-sm text-slate-300">
                        Pemesanan via QR Code Meja
                    </p>
                </div>

                {{-- Right: meja info --}}
                <div class="text-left sm:text-right">
                    @if(session('meja_id'))
                        <p class="text-sm font-semibold text-white">
                            Meja: {{ session('meja_nama') }}
                        </p>
                        <p class="text-xs text-slate-400">
                            ID: {{ session('meja_id') }}
                        </p>
                    @else
                        <p class="text-sm text-slate-300">Belum memilih meja</p>
                    @endif
                </div>

                {{-- Cart button --}}
                <div>
                    <button
                        type="button"
                        x-on:click="bukaKeranjang = true"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-white/10 hover:border-white/25"
                    >
                        <span><i data-feather="shopping-cart"></i></span>
                        @livewire(\App\Livewire\Pelanggan\KeranjangBadge::class)
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- Main --}}
    <main class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
        @if(session('pesan'))
            <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                {{ session('pesan') }}
            </div>
        @endif

        @hasSection('konten')
            @yield('konten')
        @else
            {{ $slot }}
        @endif
    </main>

    {{-- Drawer Keranjang (Global) --}}
    <div
        x-show="bukaKeranjang"
        x-transition.opacity
        class="fixed inset-0 z-40 bg-black/60"
        x-on:click="bukaKeranjang=false"
        style="display:none;"
    ></div>

    <div
        x-show="bukaKeranjang"
        x-transition
        class="fixed right-0 top-0 z-50 h-full w-[min(92vw,420px)] border-l border-white/10 bg-slate-950/90 shadow-[0_30px_90px_rgba(0,0,0,0.7)] backdrop-blur"
        x-on:keydown.escape.window="bukaKeranjang=false"
        style="display:none;"
    >
        <div class="flex items-center justify-between border-b border-white/10 p-4">
            <p class="text-base font-bold text-white">Keranjang</p>
            <button
                type="button"
                x-on:click="bukaKeranjang=false"
                class="rounded-xl border border-white/15 bg-white/5 px-3 py-1.5 text-sm font-semibold text-white transition hover:bg-white/10"
            >
                Tutup
            </button>
        </div>

        <div class="p-4">
            <livewire:pelanggan.keranjang-mini />
        </div>
    </div>

    @livewireScripts

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script>
    function bootNotyfBridge() {
        window.notyf = window.notyf || new Notyf({
            duration: 2500,
            position: { x: 'right', y: 'top' },
        });

        if (window.Livewire && !window.__notyfLivewireBound) {
            window.__notyfLivewireBound = true;

            Livewire.on('notyf', (payload = {}) => {
                const type = payload.type ?? payload.tipe ?? 'success';
                const message = payload.message ?? payload.pesan ?? 'OK';
                window.notyf.open({ type, message });
            });
        }

        @if (session()->has('notyf'))
        const data = @json(session('notyf'));
        window.notyf.open({
            type: data.type ?? data.tipe ?? 'success',
            message: data.message ?? data.pesan ?? 'OK',
        });
        @endif
    }

    bootNotyfBridge();
    document.addEventListener('livewire:init', bootNotyfBridge);
    document.addEventListener('livewire:navigated', bootNotyfBridge);
    </script>

    @if (session()->has('notyf'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const d = @json(session('notyf'));
                document.dispatchEvent(new CustomEvent('notyf', { detail: d }));
            });
        </script>
    @endif
</body>
</html>
