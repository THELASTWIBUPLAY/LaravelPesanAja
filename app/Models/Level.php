<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    // 🎯 FIX: Tambahkan 'extra_cost' ke fillable
    protected $fillable = ['name', 'code', 'description', 'extra_cost'];

    /**
     * Relasi: Level digunakan di BANYAK OrderItem.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}