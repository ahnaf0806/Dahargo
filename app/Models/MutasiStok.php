<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MutasiStok extends Model
{
    use HasFactory;

    protected $table = 'mutasi_stok';

    protected $fillable = [
        'menu_id',
        'tipe', 'jumlah',
        'sebelum', 'sesudah',
        'pesanan_id',
        'dibuat_oleh',
        'alasan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'sebelum' => 'integer',
        'sesudah' => 'integer',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
