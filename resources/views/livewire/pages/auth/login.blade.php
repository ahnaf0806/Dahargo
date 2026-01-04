<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('admin.meja.index', absolute: false), navigate: true);
    }
}; ?>

<div class="fixed inset-0 z-50 bg-[#0b1220] flex items-center justify-center p-6">
  <div class="w-full max-w-[420px]">
    <div class="relative overflow-hidden rounded-[28px]
                bg-gradient-to-b from-[#0f2a4a] to-[#071a2f]
                shadow-[0_20px_70px_rgba(0,0,0,0.45)]
                ring-1 ring-white/10">
      <div class="pointer-events-none absolute inset-0 opacity-25">
        <svg class="absolute -top-8 left-0 w-full" viewBox="0 0 700 180" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M-10 120C120 40 240 180 360 100C470 30 560 90 710 40" stroke="white" stroke-width="3" stroke-linecap="round"/>
        </svg>
        <svg class="absolute -bottom-14 left-0 w-full" viewBox="0 0 700 240" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M-20 170C110 70 230 260 350 150C470 40 560 170 720 70" stroke="white" stroke-width="3" stroke-linecap="round"/>
        </svg>
      </div>

      <div class="relative px-8 pt-7 pb-8 sm:px-10 sm:pt-8">
        <!-- top -->
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-2xl bg-white/10 grid place-items-center shadow-inner ring-1 ring-white/10">
              <span class="text-sm font-extrabold tracking-wide text-white">DG</span>
            </div>
            <div class="leading-tight">
              <div class="font-semibold text-white">DaharGo</div>
              <div class="text-[11px] text-white/70 -mt-0.5">Admin Panel</div>
            </div>
          </div>
        </div>

        <!-- title -->
        <div class="mt-10 text-center">
          <h1 class="text-3xl font-extrabold tracking-wide text-white">LOGIN</h1>
          <p class="mt-1 text-[11px] tracking-[0.35em] text-white/70">WELCOME BACK!</p>
        </div>

        <!-- status -->
        @if (session('status'))
          <div class="mt-6 rounded-2xl bg-white/10 px-4 py-3 text-sm text-white/90 ring-1 ring-white/10">
            <x-auth-session-status :status="session('status')" />
          </div>
        @endif

        <!-- form -->
        <form wire:submit.prevent="login" class="mt-7 space-y-4">
          @csrf

          <div>
            <label for="email" class="sr-only">Email</label>
            <input
              wire:model="form.email"
              id="email"
              type="email"
              name="email"
              required
              autofocus
              autocomplete="username"
              placeholder="Email"
              class="w-full rounded-full bg-white px-5 py-3 text-sm text-slate-800 placeholder:text-slate-400
                     shadow-sm outline-none ring-1 ring-black/5 focus:ring-2 focus:ring-white/60"
            />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-xs text-red-100" />
          </div>

          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              wire:model="form.password"
              id="password"
              type="password"
              name="password"
              required
              autocomplete="current-password"
              placeholder="Password"
              class="w-full rounded-full bg-white px-5 py-3 text-sm text-slate-800 placeholder:text-slate-400
                     shadow-sm outline-none ring-1 ring-black/5 focus:ring-2 focus:ring-white/60"
            />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-xs text-red-100" />
          </div>

          <div class="flex items-center justify-between pt-1">
            <label for="remember" class="flex items-center gap-2 text-xs text-white/85">
              <input
                wire:model="form.remember"
                id="remember"
                type="checkbox"
                name="remember"
                class="h-4 w-4 rounded border-white/40 bg-white/10 text-white focus:ring-white/60"
              >
              <span>Remember Me</span>
            </label>

            @if (Route::has('password.request'))
              <a
                href="{{ route('password.request') }}"
                wire:navigate
                class="text-xs text-white/85 hover:text-white underline underline-offset-4"
              >
                Forgot password?
              </a>
            @endif
          </div>

          <button
            type="submit"
            class="w-full rounded-full bg-[#0a3a66] py-3 text-sm font-semibold text-white
                   shadow-[0_10px_30px_rgba(0,0,0,0.25)]
                   hover:bg-[#083457] active:scale-[0.99] transition"
          >
            Login
          </button>
        </form>
      </div>

    </div>
  </div>
</div>