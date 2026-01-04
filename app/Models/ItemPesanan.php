<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemPesanan extends Model
{
    use HasFactory;

    protected $table = 'item_pesanan';

    protected $fillable = [
        'pesanan_id', 'menu_id',
        'nama_menu_snapshot', 'harga_snapshot',
        'jumlah', 'total_baris',
        'catatan',
    ];

    protected $casts = [
        'harga_snapshot' => 'integer',
        'jumlah' => 'integer',
        'total_baris' => 'integer',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
