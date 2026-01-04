<x-admin-layout>
    <div class="space-y-6">

        <div>
            <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500">Ringkasan aktivitas hari ini</p>
        </div>

        {{-- STAT CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <p class="text-sm text-gray-500">Pesanan Hari Ini</p>
                <p class="text-3xl font-semibold">{{ $pesananHariIni }}</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <p class="text-sm text-gray-500">Menunggu</p>
                <p class="text-3xl font-semibold">{{ $pesananMenunggu }}</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <p class="text-sm text-gray-500">Diproses</p>
                <p class="text-3xl font-semibold">{{ $pesananDiproses }}</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <p class="text-sm text-gray-500">Total Meja</p>
                <p class="text-3xl font-semibold">{{ $totalMeja }}</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <p class="text-sm text-gray-500">Stok Rendah</p>
                <p class="text-3xl font-semibold">{{ $stokRendah }}</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <p class="text-sm text-gray-500">Omzet Hari Ini</p>
                <p class="text-3xl font-semibold">
                    Rp {{ number_format($omzetHariIni, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- QUICK ACTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <a href="{{ route('admin.meja.index') }}" class="bg-white p-5 rounded-xl border hover:bg-gray-50">
                <p class="font-semibold">Kelola Meja</p>
                <p class="text-sm text-gray-500">Tambah & atur meja</p>
            </a>

            <a href="{{ route('admin.menu.index') }}" class="bg-white p-5 rounded-xl border hover:bg-gray-50">
                <p class="font-semibold">Kelola Menu</p>
                <p class="text-sm text-gray-500">Atur menu & harga</p>
            </a>

            <a href="{{ route('admin.stok.rendah') }}" class="bg-white p-5 rounded-xl border hover:bg-gray-50">
                <p class="font-semibold">Stok Rendah</p>
                <p class="text-sm text-gray-500">Perlu restock</p>
            </a>
        </div>

    </div>
</x-admin-layout>
