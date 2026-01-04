<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DaharGo') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- penting untuk Livewire --}}
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-100 min-h-screen">
@php
    $menu = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard.index', 'active' => request()->routeIs('admin.dashboard.*')],
        ['label' => 'Meja', 'route' => 'admin.meja.index', 'active' => request()->routeIs('admin.meja.*')],
        ['label' => 'Pesanan', 'route' => 'admin.pesanan.index', 'active' => request()->routeIs('admin.pesanan.*')],
        ['label' => 'Stok Rendah', 'route' => 'admin.stok.rendah', 'active' => request()->routeIs('admin.stok.*')],
        ['label' => 'Menu', 'route' => 'admin.menu.index', 'active' => request()->routeIs('admin.menu.*')],
        ['label' => 'Laporan Omzet', 'route' => 'admin.laporan.omzet', 'active' => request()->routeIs('admin.laporan.omzet*')],
    ];

    if (auth()->check() && auth()->user()->role === 'superadmin') {
        $menu[] = ['label' => 'Kelola Admin', 'route' => 'admin.admins.index', 'active' => request()->routeIs('admin.admins.*')];
    }
@endphp

<div class="min-h-screen">
    {{-- overlay mobile --}}
    <div id="adminOverlay" class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"></div>

    <div class="flex min-h-screen ">
        {{-- SIDEBAR --}}
        <aside id="adminSidebar"
            class="fixed inset-y-0 left-0 z-40 w-64 min-w-64 max-w-64 min-h-screen -translate-x-full bg-slate-800 text-slate-100 transition-transform duration-200 lg:translate-x-0  lg:transform-none lg:inset-auto lg:overflow-visible lg:h-auto h-screen overflow-hidden flex flex-col ">
            <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
                <div class="h-10 w-10 rounded-lg bg-white/10 flex items-center justify-center">
                    <span class="text-lg font-bold">D</span>
                </div>
                <div>
                    <div class="text-lg font-semibold leading-tight">{{ config('app.name', 'DaharGo') }}</div>
                    <div class="text-xs text-slate-300">Admin Panel</div>
                </div>
            </div>

            <nav class="px-3 py-4 space-y-1">
                @foreach($menu as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium
                              {{ $item['active'] ? 'bg-blue-600 text-white' : 'text-slate-200 hover:bg-white/10' }}">
                        <span class="h-2 w-2 rounded-full {{ $item['active'] ? 'bg-white' : 'bg-slate-400' }}"></span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto px-5 py-4 text-xs text-slate-400 border-t border-white/10">
                © {{ date('Y') }} {{ config('app.name','DaharGo') }}
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1 min-h-screen min-w-0 lg:ml-64 overflow-x-scroll">
            {{-- TOPBAR --}}
            <header class="sticky top-0 z-20 border-b bg-white/90 backdrop-blur">
                <div class="flex items-center justify-between px-4 py-3 lg:px-6">
                    <div class="flex items-center gap-3">
                        <button id="adminSidebarBtn"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm hover:bg-gray-50 lg:hidden">
                            ☰
                        </button>
                        <div class="text-sm text-gray-500">
                            Admin
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- notif pesanan masuk (sudah ada di project Anda) --}}
                        <livewire:admin.notifikasi-pesanan-masuk :enablePoll="!request()->routeIs('admin.pesanan.*')" />

                        {{-- profile dropdown --}}
                        <div class="relative">
                            <button id="profileBtn" type="button"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm hover:bg-gray-50">
                                <span class="h-7 w-7 rounded-full bg-gray-200 inline-flex items-center justify-center text-xs font-semibold text-gray-700">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                </span>
                                <span class="hidden sm:inline font-medium text-gray-800">
                                    {{ auth()->user()->name ?? 'Administrator' }}
                                </span>
                                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.24 4.5a.75.75 0 01-1.08 0l-4.24-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div id="profileMenu"
                                class="absolute right-0 mt-2 hidden w-56 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg z-50">
                                <div class="px-4 py-3">
                                    <p class="text-xs text-gray-500">Login sebagai</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->email }}</p>
                                </div>

                                <div class="h-px bg-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="m-0 px-4 py-6 lg:px-6">
                {{-- HEADER SLOT (kompatibel seperti x-app-layout) --}}
                @if (isset($header))
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script>
    function bootNotyfBridge() {
        window.notyf = window.notyf || new Notyf({
        duration: 2500,
        position: { x: 'right', y: 'top' },
        dismissible: true,
        types: [
            { type: 'pesanan', background: '#2563eb', icon: false },
            { type: 'success', background: '#16a34a', icon: false },
            { type: 'error',   background: '#dc2626', icon: false },
            { type: 'warning', background: '#f59e0b', icon: false },
            { type: 'info',    background: '#0ea5e9', icon: false },
        ],
        });

        // bind sekali saja
        if (window.Livewire && !window.__notyfLivewireBound) {
        window.__notyfLivewireBound = true;

        Livewire.on('notyf', (payload = {}) => {
            let type = payload.type ?? payload.tipe ?? 'success';
            const message = payload.message ?? payload.pesan ?? 'OK';

            if (type === 'sukses') type = 'pesanan';

            window.notyf.open({ type, message });
        });
        }

        // flash dari server
        @if (session()->has('notyf'))
        (function(){
            const data = @json(session('notyf'));
            let type = data.type ?? data.tipe ?? 'success';
            if (type === 'sukses') type = 'pesanan';
            window.notyf.open({ type, message: data.message ?? data.pesan ?? 'OK' });
        })();
        @endif
    }

    bootNotyfBridge();
    document.addEventListener('livewire:init', bootNotyfBridge);
    document.addEventListener('livewire:navigated', bootNotyfBridge);
    </script>

    <script>
    (function () {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');
        const btn = document.getElementById('adminSidebarBtn');
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden'); }
            if (btn) btn.addEventListener('click', openSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
            })();
        (function () {
            const btn = document.getElementById('profileBtn');
            const menu = document.getElementById('profileMenu');
            if (!btn || !menu) return;
            function open() {
                menu.classList.remove('hidden');
                }
                function close() {
                    menu.classList.add('hidden');
                    }
                    function toggle() {
                        menu.classList.contains('hidden') ? open() : close();
                        }
                        btn.addEventListener('click', (e) => { e.stopPropagation();
                        toggle();
                        });
            document.addEventListener('click', close);
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close();
            }); })();
    </script>
</body>
</html>
