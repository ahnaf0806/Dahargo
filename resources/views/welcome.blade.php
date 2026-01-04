<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes auroraMove1 {
            0%   { transform: translate(-10%, -8%) scale(1); opacity: .55; }
            50%  { transform: translate(8%, 6%) scale(1.08); opacity: .85; }
            100% { transform: translate(-10%, -8%) scale(1); opacity: .55; }
        }
        @keyframes auroraMove2 {
            0%   { transform: translate(10%, 6%) scale(1); opacity: .45; }
            50%  { transform: translate(-6%, -10%) scale(1.12); opacity: .80; }
            100% { transform: translate(10%, 6%) scale(1); opacity: .45; }
        }
        @keyframes auroraMove3 {
            0%   { transform: translate(-4%, 10%) scale(1); opacity: .35; }
            50%  { transform: translate(12%, -6%) scale(1.10); opacity: .70; }
            100% { transform: translate(-4%, 10%) scale(1); opacity: .35; }
        }
    </style>
</head>

<body class="antialiased font-sans bg-black text-white">
    <div class="relative min-h-screen overflow-hidden">
        <!-- AURORA biru cerah (mirip hijau di contoh) -->
        <div class="pointer-events-none absolute inset-0">
            <!-- base darkness -->
            <div class="absolute inset-0 bg-black"></div>

            <!-- aurora layers -->
            <div class="absolute -top-52 -left-44 h-[720px] w-[820px] rounded-full blur-3xl"
                 style="background: radial-gradient(circle at 30% 40%, rgba(0, 179, 255, 0.75), transparent 60%);
                        animation: auroraMove1 10s ease-in-out infinite;"></div>

            <div class="absolute top-10 -right-56 h-[760px] w-[900px] rounded-full blur-3xl"
                 style="background: radial-gradient(circle at 60% 40%, rgba(0, 132, 255, 0.65), transparent 62%);
                        animation: auroraMove2 12s ease-in-out infinite;"></div>

            <div class="absolute -bottom-60 left-1/4 h-[760px] w-[980px] rounded-full blur-3xl"
                 style="background: radial-gradient(circle at 50% 60%, rgba(0, 115, 255, 0.55), transparent 65%);
                        animation: auroraMove3 14s ease-in-out infinite;"></div>

            <!-- vignette + sedikit gelap supaya teks tajam -->
            <div class="absolute inset-0 bg-black/55"></div>
            <div class="absolute inset-0"
                 style="background: radial-gradient(circle at center, transparent 35%, rgba(47, 42, 42, 0.78) 100%);"></div>
        </div>

        <!-- content -->
        <main class="relative z-10">
            <section class="min-h-screen flex items-center justify-center px-6">
                <div class="w-full max-w-2xl text-center">
                    <!-- logo besar -->
                    <div class="flex justify-center">
                        <img
                            src="{{ asset('images/dahargo-logo.png') }}"
                            alt="Logo"
                            class="h-28 sm:h-32 md:h-40 w-auto drop-shadow-[0_18px_45px_rgba(0,0,0,0.7)]"
                            onerror="this.style.display='none'"
                        />
                    </div>

                    <h1 class="mt-8 text-4xl sm:text-6xl font-extrabold tracking-[0.12em]">
                        WELCOME ADMIN
                    </h1>

                    <p class="mt-4 text-sm sm:text-base text-white/70 leading-relaxed">
                        Silakan login untuk masuk ke Admin Panel.
                    </p>

                    <!-- tombol login & daftar -->
                    <div class="mt-10 flex items-center justify-center gap-3">
                        @if (Route::has('login'))
                            <a
                                href="{{ route('login') }}"
                                wire:navigate
                                class="rounded-full bg-white/10 px-8 py-3 text-xs font-semibold tracking-[0.20em] uppercase
                                       ring-1 ring-white/15 hover:bg-white/15 transition"
                            >
                                Login
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                wire:navigate
                                class="rounded-full bg-[#0ea5e9]/80 px-8 py-3 text-xs font-semibold tracking-[0.20em] uppercase
                                       ring-1 ring-white/10 hover:bg-[#0ea5e9] transition"
                            >
                                Daftar
                            </a>
                        @endif
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>