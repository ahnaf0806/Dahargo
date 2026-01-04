<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pesanan_id',
        'metode', 'jumlah', 'status',
        'path_bukti', 'no_referensi', 'waktu_bayar',
        'diverifikasi_oleh', 'waktu_verifikasi', 'catatan_admin',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'waktu_bayar' => 'datetime',
        'waktu_verifikasi' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
