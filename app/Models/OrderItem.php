<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    // Pastikan 'level_id' dan 'notes' ada di sini (Punya kamu udah bener)
    protected $fillable = ['order_id', 'menu_id', 'level_id', 'quantity', 'unit_price', 'notes'];

    /**
     * Relasi: OrderItem dimiliki oleh SATU Order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi: OrderItem merujuk ke SATU Menu.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Relasi: OrderItem merujuk ke SATU Level (Bisa NULL).
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}