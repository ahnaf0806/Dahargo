<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class MasukMejaController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $token, Request $request)
    {
        $meja = Meja::query()
            ->where('token_qr', $token)
            ->where('aktif', true)
            ->first();

        abort_if(!$meja, 404);
        
        $tokenTamu = $request->cookie('token_tamu');
        if (!$tokenTamu){
            $tokenTamu = (string) Str::uuid();
        }

        session([
            'meja_id' => $meja->id,
            'meja_nama' => $meja->nama,
            'meja_token_qr' => $meja->token_qr,
        ]);

        return redirect()
            ->route('pelanggan.menu')
            ->cookie('token_tamu', $tokenTamu, 60 * 24 * 30);

    }
}
