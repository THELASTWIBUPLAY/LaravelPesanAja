<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = ['category_id', 'name', 'description', 'image', 'price', 'has_level'];

    // Relasi: Menu dimiliki oleh SATU Category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Menu muncul di BANYAK OrderItem
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}