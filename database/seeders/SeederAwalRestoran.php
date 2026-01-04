<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\KategoriMenu;
use App\Models\Meja;
use App\Models\Menu;

class SeederAwalRestoran extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::firstOrCreate(
            ['email' => 'admin@resto.test'],
            [
                'name' => 'Admin Resto',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        $kategoriMakanan = KategoriMenu::firstOrCreate(['nama' => 'Makanan'], ['urutan' => 1]);
        $kategoriMinuman = KategoriMenu::firstOrCreate(['nama' => 'Minuman'], ['urutan' => 2]);
        $kategoriCemilan = KategoriMenu::firstOrCreate(['nama' => 'Cemilan'], ['urutan' => 3]);

        for ($i = 1; $i <= 10; $i++) {
            Meja::firstOrCreate(
                ['nama' => 'Meja ' . str_pad((string)$i, 2, '0', STR_PAD_LEFT)],
                [
                    'lokasi' => $i <= 5 ? 'Lantai 1' : 'Lantai 2',
                    'token_qr' => Str::random(48),
                    'aktif' => true,
                ]
            );
        }

        $this->seedMenuKategori($kategoriMakanan, [
            'Nasi Goreng', 'Mie Goreng', 'Ayam Geprek', 'Sate Ayam', 'Bakso',
        ], 15000, 45000);

        $this->seedMenuKategori($kategoriMinuman, [
            'Es Teh', 'Es Jeruk', 'Kopi Hitam', 'Kopi Susu', 'Air Mineral',
        ], 5000, 25000);

        $this->seedMenuKategori($kategoriCemilan, [
            'Kentang Goreng', 'Pisang Goreng', 'Tahu Crispy', 'Roti Bakar', 'Siomay',
        ], 8000, 30000);
    }

    private function seedMenuKategori(KategoriMenu $kategori, array $namaMenu, int $hargaMin, int $hargaMax): void
    {
        foreach ($namaMenu as $nama) {
            $menu = Menu::firstOrCreate(
                ['kategori_menu_id' => $kategori->id, 'nama' => $nama],
                [
                    'deskripsi' => 'Deskripsi ' . $nama,
                    'harga' => random_int($hargaMin, $hargaMax),
                    'aktif' => true,
                    'ambang_stok_rendah' => random_int(3, 8),
                ]
            );

            if ($menu->wasRecentlyCreated) {
                $menu->stok_fisik = random_int(0, 50);
                $menu->stok_dipesan = 0;
                $menu->save();
            }
        }
    }
}
