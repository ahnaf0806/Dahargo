@extends('pelanggan.layouts.pelanggan', ['judul' => 'Daftar Menu'])

@section('konten')
    <div class="rounded-xl border bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-xl font-semibold">Daftar Menu</h1>

            <a href="{{ route('pelanggan.scan') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50">
                Scan Ulang
            </a>
        </div>

        <p class="mt-3 text-sm text-gray-600">
            Halaman ini masih <span class="font-medium">placeholder</span>. Pada Bagian B nanti akan diganti
            Livewire untuk daftar menu + keranjang.
        </p>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-gray-200 p-4">
                <p class="text-sm text-gray-500">Meja Aktif</p>
                <p class="mt-1 text-base font-semibold text-gray-900">{{ session('meja_nama') }}</p>
                <p class="text-xs text-gray-500">Meja ID: {{ session('meja_id') }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 p-4">
                <p class="text-sm text-gray-500">Token Tamu (Cookie)</p>
                <p class="mt-1 break-all rounded bg-gray-50 p-2 font-mono text-xs text-gray-800">
                    {{ request()->cookie('token_tamu') }}
                </p>
                <p class="mt-2 text-xs text-gray-500">
                    Token ini dipakai untuk riwayat pesanan tanpa login.
                </p>
            </div>
        </div>
    </div>
@endsection
