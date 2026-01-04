<?php

namespace App\Livewire\Pelanggan;

use App\Models\ItemPesanan;
use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Services\StokService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Checkout extends Component
{
    use WithFileUploads;

    public string $metode_pembayaran = 'tunai'; // tunai | transfer
    public ?string $no_referensi = null;
    public $bukti_transfer = null; // Livewire upload
    public ?string $catatan_pelanggan = null;

    public function getKeranjangProperty(): array
    {
        return session()->get('keranjang', []);
    }

    public function mount(): void
    {
        if (empty($this->keranjang)) {
            redirect()->route('pelanggan.menu')->send();
        }
    }

    public function aturanValidasi(): array
    {
        return [
            'metode_pembayaran' => ['required', Rule::in(['tunai', 'transfer'])],
            'catatan_pelanggan' => ['nullable', 'string', 'max:500'],
            'no_referensi' => ['nullable', 'string', 'max:80'],
            'bukti_transfer' => [
                Rule::requiredIf($this->metode_pembayaran === 'transfer'),
                'nullable',
                'image',
                'max:2048', // 2MB
            ],
        ];
    }

    private function buatKodePesanan(): string
    {
        return 'PSN-' . now()->format('YmdHis') . '-' . random_int(100, 999);
    }

    public function buatPesanan(StokService $stokService)
    {
        $this->validate($this->aturanValidasi());

        $mejaId = session('meja_id');
        $tokenTamu = request()->cookie('token_tamu') ?: (string) Str::uuid();

        if (!$mejaId) {
            $this->dispatch('notyf', tipe: 'error', pesan: 'Meja belum dipilih. Scan QR dulu.');
            return;
        }

        $keranjang = $this->keranjang;
        if (empty($keranjang)) {
            $this->dispatch('notyf', tipe: 'peringatan', pesan: 'Keranjang kosong.');
            return;
        }

        try {
            $pesanan = DB::transaction(function () use ($stokService, $keranjang, $mejaId, $tokenTamu) {

                // hitung ulang dari DB (anti manipulasi)
                $menuDb = Menu::query()
                    ->whereIn('id', array_keys($keranjang))
                    ->where('aktif', true)
                    ->get()
                    ->keyBy('id');

                $subtotal = 0;
                foreach ($keranjang as $menuId => $jumlah) {
                    $m = $menuDb->get((int)$menuId);
                    if (!$m) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'keranjang' => 'Ada item yang tidak valid/disable.',
                        ]);
                    }
                    $subtotal += ((int)$m->harga) * (int)$jumlah;
                }

                $kode = $this->buatKodePesanan();

                $pesanan = Pesanan::create([
                    'kode' => $kode,
                    'meja_id' => $mejaId,
                    'token_tamu' => $tokenTamu,

                    'subtotal' => $subtotal,
                    'pajak' => 0,
                    'biaya_layanan' => 0,
                    'diskon' => 0,
                    'total' => $subtotal,

                    'status' => Pesanan::STATUS_MENUNGGU,
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'status_pembayaran' => $this->metode_pembayaran === 'transfer'
                        ? Pesanan::BAYAR_MENUNGGU_VERIF
                        : Pesanan::BAYAR_BELUM,

                    'waktu_pesan' => now(),
                    'catatan_pelanggan' => $this->catatan_pelanggan,
                ]);

                // Reservasi stok (LOCK row menu)
                $stokService->reservasi($keranjang, $pesanan->id, null);

                // Simpan item snapshot
                foreach ($keranjang as $menuId => $jumlah) {
                    $m = $menuDb->get((int)$menuId);

                    ItemPesanan::create([
                        'pesanan_id' => $pesanan->id,
                        'menu_id' => $m->id,
                        'nama_menu_snapshot' => $m->nama,
                        'harga_snapshot' => (int)$m->harga,
                        'jumlah' => (int)$jumlah,
                        'total_baris' => (int)$m->harga * (int)$jumlah,
                        'catatan' => null,
                    ]);
                }

                // Pembayaran (selalu dibuat agar admin tinggal verifikasi)
                $pathBukti = null;
                $waktuBayar = null;

                if ($this->metode_pembayaran === 'transfer') {
                    $ext = $this->bukti_transfer->getClientOriginalExtension();
                    $pathBukti = $this->bukti_transfer->storeAs(
                        'bukti-transfer',
                        $pesanan->kode . '.' . $ext,
                        'public'
                    );
                    $waktuBayar = now();
                }

                Pembayaran::create([
                    'pesanan_id' => $pesanan->id,
                    'metode' => $this->metode_pembayaran,
                    'jumlah' => (int)$pesanan->total,
                    'status' => 'menunggu',
                    'path_bukti' => $pathBukti,
                    'no_referensi' => $this->no_referensi,
                    'waktu_bayar' => $waktuBayar,
                ]);

                return $pesanan;
            });

            session()->forget('keranjang');

            session()->flash('notyf', [
                'type' => 'success',
                'message' => 'Pesanan berhasil dibuat!',
            ]);

            return redirect()->route('pelanggan.pesanan.status', ['kode' => $pesanan->kode]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('notyf', tipe: 'error', pesan: 'Gagal membuat pesanan. Coba lagi.');
        }
    }

    public function render()
    {
        $keranjang = $this->keranjang;

        $daftar = collect();
        $subtotal = 0;

        if (!empty($keranjang)) {
            $menu = Menu::query()->whereIn('id', array_keys($keranjang))->get()->keyBy('id');
            foreach ($keranjang as $id => $qty) {
                $m = $menu->get((int)$id);
                if (!$m) continue;

                $total = (int)$m->harga * (int)$qty;
                $subtotal += $total;

                $daftar->push([
                    'nama' => $m->nama,
                    'harga' => (int)$m->harga,
                    'jumlah' => (int)$qty,
                    'total_baris' => $total,
                ]);
            }
        }

        return view('livewire.pelanggan.checkout', [
            'daftar' => $daftar,
            'subtotal' => $subtotal,
        ])->layout('pelanggan.layouts.pelanggan', [
            'judul' => 'Checkout',
        ]);
    }
}
