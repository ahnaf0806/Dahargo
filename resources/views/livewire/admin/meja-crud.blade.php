<div class="space-y-4">
    @if(session('pesan_sukses'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('pesan_sukses') }}
        </div>
    @endif

    {{-- Generate batch --}}
    <div class="rounded-lg bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div>
                <div class="text-lg font-semibold text-gray-800">Generate Meja</div>
                <div class="text-sm text-gray-500">Buat meja sekaligus + token QR otomatis.</div>
            </div>
            <button wire:click="bukaTambah"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                + Tambah Meja
            </button>
        </div>
    </div>

    {{-- List --}}
    <div class="rounded-lg bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between bg-slate-800 px-4 py-3">
            <div class="font-semibold text-white">Daftar Meja</div>
            <input wire:model.live="cari" class="w-60 rounded-md border-0 bg-white/10 text-sm text-white placeholder-white/70 focus:ring-0"
                   placeholder="Cari meja...">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Link</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                    <th class="px-4 py-3 text-left">QR</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
                </thead>

                <tbody class="divide-y">
                @forelse($daftar as $m)
                    @php $url = url('/m/'.$m->token_qr); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold">{{ $m->nama }}</td>
                        <td class="px-4 py-3">
                            <a class="text-blue-600 hover:underline" href="{{ $url }}" target="_blank">{{ $url }}</a>
                            <button class="ml-2 text-xs text-gray-500 hover:text-gray-900"
                                    onclick="navigator.clipboard.writeText('{{ $url }}')">
                                Copy
                            </button>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $m->lokasi ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $qrSvgKecil = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                    ->size(90)->margin(1)->generate($url);
                                $qrSvgBesar = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                    ->size(240)->margin(1)->generate($url);
                            @endphp

                            <details class="relative inline-block">
                                <summary class="list-none cursor-pointer">
                                    <div class="h-20 w-20 rounded border bg-white p-1 hover:ring-2 hover:ring-blue-200">
                                        {!! $qrSvgKecil !!}
                                    </div>
                                </summary>

                                <div class="absolute right-0 z-20 mt-2 w-44 rounded-md border bg-white p-1 shadow-lg">
                                    <button type="button"
                                        onclick="printQrSvg(@js($m->nama), @js($url), @js($qrSvgBesar)); this.closest('details').removeAttribute('open');"
                                        class="w-full rounded px-3 py-2 text-left text-sm hover:bg-gray-50">
                                        Print QR
                                    </button>
                                </div>
                            </details>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $m->aktif ? 'bg-green-100 text-green-700':'bg-gray-100 text-gray-700' }}">
                                {{ $m->aktif ? 'aktif' : 'nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <button wire:click="bukaEdit({{ $m->id }})"
                                        class="rounded-md border border-gray-200 bg-white px-3 py-2 text-xs font-semibold hover:bg-gray-50">
                                    Edit
                                </button>
                                <button wire:click="toggleAktif({{ $m->id }})"
                                        class="rounded-md border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100">
                                    Toggle
                                </button>
                                <button wire:click="regenerasiToken({{ $m->id }})"
                                        class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-100">
                                    Regenerate QR
                                </button>
                                <button wire:click="hapus({{ $m->id }})"
                                        class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-gray-500">Belum ada meja.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">{{ $daftar->links() }}</div>
    </div>

    {{-- Modal --}}
    @if($modal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="w-full max-w-lg rounded-lg bg-white p-5 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="text-lg font-bold">{{ $editId ? 'Edit Meja' : 'Tambah Meja' }}</div>
                    <button wire:click="$set('modal', false)" class="text-gray-500 hover:text-gray-900">✕</button>
                </div>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama</label>
                        <input wire:model="nama" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        @error('nama') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Lokasi</label>
                        <input wire:model="lokasi" class="mt-1 w-full rounded-md border-gray-300 text-sm" placeholder="Contoh: Lantai 1 / VIP / Indoor">
                        @error('lokasi') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="aktif" class="rounded border-gray-300">
                        Aktif
                    </label>
                </div>

                <div class="mt-5 flex justify-end gap-2">
                    <button wire:click="$set('modal', false)"
                            class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-gray-50">
                        Batal
                    </button>
                    <button wire:click="simpan"
                            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif
    <div class="space-y-4">
    @if(session('pesan_sukses'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('pesan_sukses') }}
        </div>
    @endif

    {{-- Modal --}}
    @if($modal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="w-full max-w-lg rounded-lg bg-white p-5 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="text-lg font-bold">{{ $editId ? 'Edit Meja' : 'Tambah Meja' }}</div>
                    <button wire:click="$set('modal', false)" class="text-gray-500 hover:text-gray-900">✕</button>
                </div>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama</label>
                        <input wire:model="nama" class="mt-1 w-full rounded-md border-gray-300 text-sm">
                        @error('nama') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Lokasi</label>
                        <input wire:model="lokasi" class="mt-1 w-full rounded-md border-gray-300 text-sm" placeholder="Contoh: Lantai 1 / VIP / Indoor">
                        @error('lokasi') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model="aktif" class="rounded border-gray-300">
                        Aktif
                    </label>
                </div>

                <div class="mt-5 flex justify-end gap-2">
                    <button wire:click="$set('modal', false)"
                            class="rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-gray-50">
                        Batal
                    </button>
                    <button wire:click="simpan"
                            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif
    @once
        <script>
        function printQrSvg(nama, url, svg){
        const svg64 = btoa(unescape(encodeURIComponent(svg)));
        const src = `data:image/svg+xml;base64,${svg64}`;

        const w = window.open('', '_blank', 'width=420,height=600');
        w.document.write(`
            <html><head><title>Print QR</title>
            <style>
                body{font-family:system-ui;padding:24px}
                .card{border:1px solid #e5e7eb;border-radius:12px;padding:16px;text-align:center}
                .muted{font-size:12px;color:#6b7280;word-break:break-all}
            </style>
            </head>
            <body>
            <div class="card">
                <div style="font-weight:700;font-size:18px">${nama}</div>
                <div style="margin-top:12px">
                <img src="${src}" style="width:260px;height:260px" />
                </div>
                <div class="muted" style="margin-top:10px">${url}</div>
            </div>
            <script>
                window.onload = () => { window.print(); window.onafterprint = () => window.close(); }
            <\/script>
            </body></html>
        `);
        w.document.close();
        }
        </script>
    @endonce
</div>
