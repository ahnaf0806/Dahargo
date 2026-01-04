<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meja;

class MejaAdminController extends Controller
{
    public function index()
    {
        $daftarMeja = Meja::query()
        ->orderBy('nama')
        ->get();

    return view('admin.meja.index', compact('daftarMeja'));
    }
}
