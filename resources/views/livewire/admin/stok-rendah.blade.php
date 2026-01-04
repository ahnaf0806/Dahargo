<x-admin-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Peringatan Stok Rendah</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-xl border bg-white shadow-sm">
                <div class="p-4 border-b text-sm text-gray-600">
                    Menu dengan stok tersedia ≤ batas stok rendah.
                </div>

                @if($menu->isEmpty())
                    <div class="p-6 text-sm text-gray-600">Aman, tidak ada stok rendah.</div>
                @else
                    <div class="divide-y">
                        @foreach($menu as $m)
                            @php $tersedia = max(0, (int)$m->stok_fisik - (int)$m->stok_dipesan); @endphp
                            <div class="p-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold">{{ $m->nama }}</p>
                                    <p class="text-xs text-gray-500">
                                        Tersedia: {{ $tersedia }} • Batas: {{ $m->batas_stok_rendah }}
                                    </p>
                                </div>

                                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700">
                                    Stok Rendah
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
