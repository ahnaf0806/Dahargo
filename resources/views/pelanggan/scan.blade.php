@extends('pelanggan.layouts.pelanggan', ['judul' => 'Scan QR Meja | DaharGo'])

@section('konten')
    <div class="rounded-xl border bg-white p-6 shadow-sm">
        <h1 class="text-xl font-semibold">Scan QR Code Meja</h1>

        <p class="mt-2 text-sm text-gray-600">
            Untuk memesan, silakan scan QR Code yang tersedia di meja Anda.
        </p>

        <div class="mt-6 rounded-lg border border-dashed bg-gray-50 p-4">
            <p class="text-sm font-medium text-gray-800">Tips untuk pemula</p>
            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-gray-600">
                <li>Pastikan Anda scan QR di meja yang benar.</li>
                <li>Jika dibuka dari HP, gunakan browser default (Chrome/Safari).</li>
                <li>Jika Anda admin, login untuk mencetak QR setiap meja.</li>
            </ul>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('login') }}"
               class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Login Admin
            </a>

            <a href="{{ url('/') }}"
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50">
                Beranda
            </a>
        </div>
    </div>
@endsection
