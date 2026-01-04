<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pesanan;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();

        return view('admin.dashboard.index', [
            'totalMeja' => Meja::count(),

            'pesananHariIni' => Pesanan::whereDate('created_at', $hariIni)->count(),

            'pesananMenunggu' => Pesanan::where('status', 'menunggu')->count(),

            'pesananDiproses' => Pesanan::where('status', 'diproses')->count(),

            'stokRendah' => Menu::whereRaw('(stok_fisik - stok_dipesan) <= 3')->count(),

            'omzetHariIni' => Pesanan::whereDate('created_at', $hariIni)
                ->where('status', 'selesai')
                ->sum('total'),
        ]);
    }
}
