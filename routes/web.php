<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasukMejaController;
use App\Http\Controllers\Admin\MejaAdminController;
use App\Livewire\Pelanggan\DaftarMenu;
use App\Http\Middleware\PastikanMejaTerpilih;
use App\Livewire\Pelanggan\Checkout;
use App\Livewire\Pelanggan\StatusPesanan;
use App\Http\Controllers\StrukController;
use App\Livewire\Admin\AntrianPesanan;
use App\Livewire\Pelanggan\RiwayatPesanan;
use App\Livewire\Admin\DetailPesanan;
use App\Livewire\Admin\StokRendah;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Livewire\Admin\AdminCrud;
use App\Http\Controllers\Admin\LaporanOmzetController;
use App\Http\Controllers\Admin\PesananStrukController;
use App\Livewire\Admin\LaporanOmzet;

Route::view('/', 'welcome');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard.index');
})->middleware(['auth'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/m/{token}',MasukMejaController::class)
    ->name('masuk.meja');

Route::view('/scan','pelanggan.scan')->name('pelanggan.scan');
Route::middleware([PastikanMejaTerpilih::class])->group(function () {
    Route::get('/menu', DaftarMenu::class)->name('pelanggan.menu');
    Route::get('/checkout', Checkout::class)->name('pelanggan.checkout');
    Route::get('/pesanan/{kode}', StatusPesanan::class)->name('pelanggan.pesanan.status');
    Route::get('/riwayat', RiwayatPesanan::class)->name('pelanggan.riwayat');
    Route::get('/struk/{kode}', [StrukController::class, 'show'])->name('pelanggan.struk');
    Route::get('/struk/{kode}/pdf', [StrukController::class, 'pdf'])->name('pelanggan.struk.pdf');
});

Route::middleware(['auth', 'user.active', 'admin.only'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])
    ->name('admin.dashboard.index');
    Route::get('/meja', [MejaAdminController::class, 'index'])
        ->name('admin.meja.index');
    Route::view('/menu', 'admin.menu.index')
        ->name('admin.menu.index');
    Route::view('/kategori', 'admin.kategori.index')
        ->name('admin.kategori.index');
    Route::get('/pesanan', AntrianPesanan::class)
        ->name('admin.pesanan.index');
    Route::get('/pesanan/{pesanan}', DetailPesanan::class)
        ->name('admin.pesanan.detail');
    Route::get('/stok-rendah', StokRendah::class)
        ->name('admin.stok.rendah');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/admins', AdminCrud::class)
        ->middleware('superadmin.only')
        ->name('admin.admins.index');
    Route::get('/laporan/omzet', LaporanOmzet::class)
        ->name('admin.laporan.omzet');
    Route::get('/laporan/omzet/pdf', [LaporanOmzetController::class, 'pdf'])
        ->name('admin.laporan.omzet.pdf');
    Route::get('/admin/pesanan/{pesanan}/Struk', [PesananStrukController::class, 'show'])
        ->middleware(['auth', 'user.active', 'admin.only'])
        ->name('admin.pesanan.struk');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');
require __DIR__.'/auth.php';