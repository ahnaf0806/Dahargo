<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pesanan;
Use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;


class LaporanOmzetController extends Controller
{
    public function index(Request $request){
        // Halaman filter
        return view('admin.laporan.omzet-index');
    }

    public function pdf(Request $request)
    {
        $data = $this->buildReportData($request);

        $pdf = Pdf::loadView('admin.laporan.omzet-pdf', $data)
            ->setPaper('A4', 'portrait');

        $filename = 'laporan-omzet-' . now()->format('Y-m-d_His') . '.pdf';
        return $pdf->download($filename);
    }
    private function buildReportData(Request $request): array
    {
        // mode: harian | bulanan | tahunan
        $validated = $request->validate([
            'mode' => 'required|in:harian,bulanan,tahunan',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $mode = $validated['mode'];
        $start = Carbon::parse($validated['start'])->startOfDay();
        $end = Carbon::parse($validated['end'])->endOfDay();

        // Definisi OMZET (rekomendasi): pembayaran lunas berdasarkan waktu_validasi
        // Sesuaikan konstanta ini dengan model Pesanan kamu.
        $base = Pesanan::query()
            ->whereBetween('waktu_validasi', [$start, $end])
            ->where('status_pembayaran', Pesanan::BAYAR_LUNAS);

        // Grouping SQL per mode
        // MySQL: DATE(), DATE_FORMAT()
        if ($mode === 'harian') {
            $rows = (clone $base)
                ->selectRaw("DATE(waktu_validasi) as periode, SUM(total) as omzet, COUNT(*) as transaksi")
                ->groupByRaw("DATE(waktu_validasi)")
                ->orderByRaw("DATE(waktu_validasi)")
                ->get()
                ->map(function ($r) {
                    return [
                        'periode' => Carbon::parse($r->periode)->format('d M Y'),
                        'transaksi' => (int) $r->transaksi,
                        'omzet' => (float) $r->omzet,
                    ];
                });
        } elseif ($mode === 'bulanan') {
            $rows = (clone $base)
                ->selectRaw("DATE_FORMAT(waktu_validasi, '%Y-%m') as periode, SUM(total) as omzet, COUNT(*) as transaksi")
                ->groupByRaw("DATE_FORMAT(waktu_validasi, '%Y-%m')")
                ->orderByRaw("DATE_FORMAT(waktu_validasi, '%Y-%m')")
                ->get()
                ->map(function ($r) {
                    $periode = Carbon::createFromFormat('Y-m', $r->periode)->format('M Y');
                    return [
                        'periode' => $periode,
                        'transaksi' => (int) $r->transaksi,
                        'omzet' => (float) $r->omzet,
                    ];
                });
        } else { // tahunan
            $rows = (clone $base)
                ->selectRaw("YEAR(waktu_validasi) as periode, SUM(total) as omzet, COUNT(*) as transaksi")
                ->groupByRaw("YEAR(waktu_validasi)")
                ->orderByRaw("YEAR(waktu_validasi)")
                ->get()
                ->map(function ($r) {
                    return [
                        'periode' => (string) $r->periode,
                        'transaksi' => (int) $r->transaksi,
                        'omzet' => (float) $r->omzet,
                    ];
                });
        }
        $totalOmzet = $rows->sum('omzet');
        $totalTransaksi = $rows->sum('transaksi');

        return [
            'mode' => $mode,
            'start' => $start,
            'end' => $end,
            'rows' => $rows,
            'totalOmzet' => $totalOmzet,
            'totalTransaksi' => $totalTransaksi,
            'dicetakPada' => now(),
        ];
    }
}
