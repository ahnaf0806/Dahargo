<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
     use HasFactory;

    protected $table = 'pesanan';

    // konstanta status (biar konsisten)
    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIKONFIRMASI = 'dikonfirmasi';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_TERSAJI = 'tersaji';
    public const STATUS_DIBATALKAN = 'dibatalkan';
    public const STATUS_SELESAI = 'selesai';

    public const BAYAR_BELUM = 'belum_bayar';
    public const BAYAR_MENUNGGU_VERIF = 'menunggu_verifikasi';
    public const BAYAR_LUNAS = 'lunas';
    public const BAYAR_DITOLAK = 'ditolak';

    protected $fillable = [
        'kode', 'meja_id', 'token_tamu',
        'subtotal', 'pajak', 'biaya_layanan', 'diskon', 'total',
        'status', 'metode_pembayaran', 'status_pembayaran',
        'waktu_pesan', 'waktu_validasi',
        'catatan_pelanggan',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'pajak' => 'integer',
        'biaya_layanan' => 'integer',
        'diskon' => 'integer',
        'total' => 'integer',
        'waktu_pesan' => 'datetime',
        'waktu_validasi' => 'datetime',
    ];

    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id');
    }

    public function item()
    {
        return $this->hasMany(ItemPesanan::class, 'pesanan_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id');
    }

    public function sudahLunas(): bool
    {
        return $this->status_pembayaran === self::BAYAR_LUNAS;
    }
}
