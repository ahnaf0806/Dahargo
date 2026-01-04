<?php

namespace App\Livewire\Admin;

use App\Models\Pesanan;
use Carbon\Carbon;
use Livewire\Component;

class LaporanOmzet extends Component
{
    public string $mode = 'harian'; // harian|bulanan|tahunan
    public string $start;
    public string $end;

    public array $rows = [];
    public float $totalOmzet = 0;
    public int $totalTransaksi = 0;

    public function mount(): void
    {
        // ambil dari querystring bila ada (biar bisa di-refresh/bookmark)
        $this->mode  = request('mode', 'harian');
        $this->start = request('start', now()->startOfMonth()->toDateString());
        $this->end   = request('end', now()->toDateString());

        $this->hitung();
    }

    public function updated($name): void
    {
        if (in_array($name, ['mode', 'start', 'end'], true)) {
            $this->hitung();
        }
    }

    public function getPdfUrlProperty(): string
    {
        return route('admin.laporan.omzet.pdf', [
            'mode' => $this->mode,
            'start' => $this->start,
            'end' => $this->end,
        ]);
    }

    public function hitung(): void
    {
        // validasi ringan
        $mode = in_array($this->mode, ['harian','bulanan','tahunan'], true) ? $this->mode : 'harian';
        $start = Carbon::parse($this->start)->startOfDay();
        $end   = Carbon::parse($this->end)->endOfDay();

        // basis omzet: pembayaran yang sudah dikonfirmasi (lunas) berdasarkan waktu_validasi
        $base = Pesanan::query()
            ->where('status_pembayaran', Pesanan::BAYAR_LUNAS)
            ->whereNotNull('waktu_validasi')
            ->whereBetween('waktu_validasi', [$start, $end]);

        if ($mode === 'harian') {
            $data = (clone $base)
                ->selectRaw("DATE(waktu_validasi) as periode, SUM(total) as omzet, COUNT(*) as transaksi")
                ->groupByRaw("DATE(waktu_validasi)")
                ->orderByRaw("DATE(waktu_validasi)")
                ->get()
                ->map(fn($r) => [
                    'periode' => Carbon::parse($r->periode)->format('d M Y'),
                    'transaksi' => (int)$r->transaksi,
                    'omzet' => (float)$r->omzet,
                ])
                ->all();
        } elseif ($mode === 'bulanan') {
            $data = (clone $base)
                ->selectRaw("DATE_FORMAT(waktu_validasi, '%Y-%m') as periode, SUM(total) as omzet, COUNT(*) as transaksi")
                ->groupByRaw("DATE_FORMAT(waktu_validasi, '%Y-%m')")
                ->orderByRaw("DATE_FORMAT(waktu_validasi, '%Y-%m')")
                ->get()
                ->map(function ($r) {
                    $periode = Carbon::createFromFormat('Y-m', $r->periode)->format('M Y');
                    return [
                        'periode' => $periode,
                        'transaksi' => (int)$r->transaksi,
                        'omzet' => (float)$r->omzet,
                    ];
                })
                ->all();
        } else { // tahunan
            $data = (clone $base)
                ->selectRaw("YEAR(waktu_validasi) as periode, SUM(total) as omzet, COUNT(*) as transaksi")
                ->groupByRaw("YEAR(waktu_validasi)")
                ->orderByRaw("YEAR(waktu_validasi)")
                ->get()
                ->map(fn($r) => [
                    'periode' => (string)$r->periode,
                    'transaksi' => (int)$r->transaksi,
                    'omzet' => (float)$r->omzet,
                ])
                ->all();
        }

        $this->mode = $mode;
        $this->rows = $data;
        $this->totalOmzet = array_sum(array_column($data, 'omzet'));
        $this->totalTransaksi = array_sum(array_column($data, 'transaksi'));
    }

    public function render()
    {
        return view('livewire.admin.laporan-omzet')
            ->layout('components.admin-layout');
    }
}