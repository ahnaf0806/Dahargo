<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;

class PesananStrukController extends Controller
{
    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['meja', 'item.menu', 'pembayaran']);
        return view('admin.pesanan.struk', compact('pesanan'));
    }

}