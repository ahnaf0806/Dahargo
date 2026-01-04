<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';

    protected $fillable = ['nama', 'lokasi', 'token_qr', 'aktif','path_foto'];

    protected $casts = ['aktif' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (Meja $meja) {
            if (empty($meja->token_qr)) {
                $meja->token_qr = Str::random(48);
            }
        });
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'meja_id');
    }
}
