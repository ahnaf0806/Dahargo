<x-slot name="header">
  <div class="flex items-center justify-between">
    <h2 class="text-xl font-semibold text-gray-800">Laporan Omzet</h2>
  </div>
  <div class="text-xs text-gray-500">Rekap Laporan Omzet.</div>
</x-slot>

<div class="mx-auto max-w-7xl sm:px-6 lg:px-8 py-6">
  <div class="rounded-xl border bg-white shadow-sm p-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
      <div>
        <label class="text-xs text-gray-500">Mode</label>
        <select wire:model.live="mode" class="mt-1 w-full rounded-lg border-gray-300">
          <option value="harian">Harian</option>
          <option value="bulanan">Bulanan</option>
          <option value="tahunan">Tahunan</option>
        </select>
      </div>

      <div>
        <label class="text-xs text-gray-500">Dari</label>
        <input type="date" wire:model.live="start" class="mt-1 w-full rounded-lg border-gray-300">
      </div>

      <div>
        <label class="text-xs text-gray-500">Sampai</label>
        <input type="date" wire:model.live="end" class="mt-1 w-full rounded-lg border-gray-300">
      </div>

      <div class="flex items-end gap-2">
        <a href="{{ $this->pdfUrl }}" target="_blank" rel="noopener"
           class="w-full text-center rounded-lg bg-blue-600 px-4 py-2 text-white font-semibold hover:bg-blue-700">
          Cetak PDF
        </a>
      </div>
    </div>
  </div>

  <div class="rounded-xl border bg-white shadow-sm mt-4">
    <div class="p-4 border-b flex items-center justify-between">
      <div class="text-sm font-semibold">Ringkasan</div>
      <div class="text-xs text-gray-500">
        Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} – {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
      </div>
    </div>

    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
      <div class="rounded-lg border p-3">
        <div class="text-xs text-gray-500">Total Transaksi</div>
        <div class="text-lg font-bold">{{ number_format($totalTransaksi) }}</div>
      </div>
      <div class="rounded-lg border p-3 md:col-span-2">
        <div class="text-xs text-gray-500">Total Omzet</div>
        <div class="text-lg font-bold">Rp {{ number_format($totalOmzet,0,',','.') }}</div>
      </div>
    </div>

    <div class="p-4 pt-0 overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="text-xs text-gray-500">
          <tr class="border-b">
            <th class="text-left py-2">Periode</th>
            <th class="text-right py-2">Transaksi</th>
            <th class="text-right py-2">Omzet</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $r)
            <tr class="border-b">
              <td class="py-2">{{ $r['periode'] }}</td>
              <td class="py-2 text-right">{{ number_format($r['transaksi']) }}</td>
              <td class="py-2 text-right">Rp {{ number_format($r['omzet'],0,',','.') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="py-6 text-center text-gray-500">Tidak ada data pada periode ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
