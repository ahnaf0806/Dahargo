<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MutasiStok;
use App\Models\Pesanan;
use Illuminate\Validation\ValidationException;

class StokService
{
    /**
     * Reservasi stok dengan menaikkan stok_dipesan.
     * $keranjang format: [menu_id => jumlah]
     */
    public function reservasi(array $keranjang, int $pesananId, ?int $dibuatOleh = null): void
    {
        foreach ($keranjang as $menuId => $jumlah) {
            $jumlah = (int) $jumlah;

            $menu = Menu::query()
                ->whereKey($menuId)
                ->lockForUpdate()
                ->firstOrFail();

            $tersedia = max(0, (int)$menu->stok_fisik - (int)$menu->stok_dipesan);

            if ($jumlah <= 0 || $jumlah > $tersedia) {
                throw ValidationException::withMessages([
                    'keranjang' => "Stok tidak cukup untuk {$menu->nama}. Tersedia: {$tersedia}.",
                ]);
            }

            $sebelum = (int)$menu->stok_dipesan;
            $menu->stok_dipesan = $sebelum + $jumlah;
            $menu->save();

            MutasiStok::create([
                'menu_id' => $menu->id,
                'tipe' => 'pesan',         // reservasi
                'jumlah' => $jumlah,       // +qty
                'sebelum' => $sebelum,     // sebelum stok_dipesan
                'sesudah' => (int)$menu->stok_dipesan,
                'pesanan_id' => $pesananId,
                'dibuat_oleh' => $dibuatOleh,
                'alasan' => 'Reservasi stok untuk pesanan',
            ]);
        }
    }

    /**
     * Lepas reservasi: turunkan stok_dipesan sesuai item pesanan.
     */
    public function lepasReservasi(Pesanan $pesanan, ?int $dibuatOleh = null): void
    {
        $pesanan->loadMissing('item');

        foreach ($pesanan->item as $item) {
            $menu = Menu::query()
                ->whereKey($item->menu_id)
                ->lockForUpdate()
                ->firstOrFail();

            $sebelum = (int)$menu->stok_dipesan;
            $menu->stok_dipesan = max(0, $sebelum - (int)$item->jumlah);
            $menu->save();

            MutasiStok::create([
                'menu_id' => $menu->id,
                'tipe' => 'lepas',
                'jumlah' => -((int)$item->jumlah), // negatif
                'sebelum' => $sebelum,
                'sesudah' => (int)$menu->stok_dipesan,
                'pesanan_id' => $pesanan->id,
                'dibuat_oleh' => $dibuatOleh,
                'alasan' => 'Lepas reservasi (pesanan ditolak/dibatalkan)',
            ]);
        }
    }

    /**
     * Komit terjual: turunkan stok_fisik dan stok_dipesan.
     */
    public function komitTerjual(Pesanan $pesanan, ?int $dibuatOleh = null): void
    {
        $pesanan->loadMissing('item');

        foreach ($pesanan->item as $item) {
            $menu = Menu::query()
                ->whereKey($item->menu_id)
                ->lockForUpdate()
                ->firstOrFail();

            $qty = (int)$item->jumlah;

            // Validasi keamanan
            if ($menu->stok_dipesan < $qty) {
                throw ValidationException::withMessages([
                    'stok' => "Reservasi stok tidak valid untuk {$menu->nama}.",
                ]);
            }
            if ($menu->stok_fisik < $qty) {
                throw ValidationException::withMessages([
                    'stok' => "Stok fisik kurang untuk {$menu->nama}.",
                ]);
            }

            // 1) kurangi stok_dipesan
            $sebelumPesan = (int)$menu->stok_dipesan;
            $menu->stok_dipesan = $sebelumPesan - $qty;

            MutasiStok::create([
                'menu_id' => $menu->id,
                'tipe' => 'lepas',
                'jumlah' => -$qty,
                'sebelum' => $sebelumPesan,
                'sesudah' => (int)$menu->stok_dipesan,
                'pesanan_id' => $pesanan->id,
                'dibuat_oleh' => $dibuatOleh,
                'alasan' => 'Komit terjual: lepas reservasi',
            ]);

            // 2) kurangi stok_fisik
            $sebelumFisik = (int)$menu->stok_fisik;
            $menu->stok_fisik = $sebelumFisik - $qty;

            $menu->save();

            MutasiStok::create([
                'menu_id' => $menu->id,
                'tipe' => 'terjual',
                'jumlah' => -$qty,
                'sebelum' => $sebelumFisik,
                'sesudah' => (int)$menu->stok_fisik,
                'pesanan_id' => $pesanan->id,
                'dibuat_oleh' => $dibuatOleh,
                'alasan' => 'Komit terjual: kurangi stok fisik',
            ]);
        }
    }
}
