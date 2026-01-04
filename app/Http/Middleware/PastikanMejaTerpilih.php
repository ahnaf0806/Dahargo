<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PastikanMejaTerpilih
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('meja_id')) {
            return redirect()
            ->route('pelanggan.scan')
            ->with('pesan', 'Silahkan Scan QR Code meja terlebih dahulu.');
        }

        return $next($request);
    }
}
