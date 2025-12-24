<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    // Relasi: Satu Kategori punya BANYAK Menu
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}