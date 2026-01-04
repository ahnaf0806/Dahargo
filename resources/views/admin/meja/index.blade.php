<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Meja</h2>
                <p class="mt-1 text-sm text-gray-500">Kelola meja dan QR pelanggan.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        <livewire:admin.meja-crud />
    </div>
</x-admin-layout>
