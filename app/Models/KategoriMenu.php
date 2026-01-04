<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriMenu extends Model
{
    use HasFactory;

    protected $table = 'kategori_menu';

    protected $fillable = ['nama', 'urutan'];

    public function menu()
    {
        return $this->hasMany(Menu::class, 'kategori_menu_id');
    }
}
