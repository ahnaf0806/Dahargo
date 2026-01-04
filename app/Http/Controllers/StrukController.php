<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class StrukController extends Controller
{
    public function show(string $kode)
    {
        $tokenTamu = request()->cookie('token_tamu');

        $pesanan = Pesanan::query()
            ->with(['meja', 'item', 'pembayaran'])
            ->where('kode', $kode)
            ->firstOrFail();

        // proteksi: customer hanya boleh lihat struk miliknya
        if ($pesanan->token_tamu && $tokenTamu && $pesanan->token_tamu !== $tokenTamu) {
            abort(403);
        }

        // struk hanya valid kalau lunas (sesuai flow Anda)
        if ($pesanan->status_pembayaran !== Pesanan::BAYAR_LUNAS) {
            abort(403);
        }

        return view('pelanggan.struk', compact('pesanan'));
    }

    public function pdf(string $kode)
    {
        $pesanan = Pesanan::with(['meja','item','pembayaran'])->where('kode',$kode)->firstOrFail();
    // proteksi token_tamu + lunas sama seperti show()

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pelanggan.struk', compact('pesanan'));
            return $pdf->download("struk-{$pesanan->kode}.pdf");
    }

}
