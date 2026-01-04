<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    // stok sengaja TIDAK masuk fillable (anti manipulasi mass assignment)
    protected $fillable = [
        'kategori_menu_id',
        'nama',
        'deskripsi',
        'harga',
        'path_foto',
        'aktif',
        'stok_fisik',
        'stok_dipesan',
        'batas_stok_rendah',
        'ambang_stok_rendah',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'harga' => 'integer',
        'stok_fisik' => 'integer',
        'stok_dipesan' => 'integer',
        'batas_stok_rendah' => 'integer',
        'ambang_stok_rendah' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_menu_id');
    }

    public function itemPesanan()
    {
        return $this->hasMany(ItemPesanan::class, 'menu_id');
    }

    public function mutasiStok()
    {
        return $this->hasMany(MutasiStok::class, 'menu_id');
    }

    public function getStokTersediaAttribute(): int
    {
        return max(0, (int)$this->stok_fisik - (int)$this->stok_dipesan);
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeStokRendah($query)
    {
        return $query->whereRaw('(stok_fisik - stok_dipesan) <= ambang_stok_rendah');
    }
}
