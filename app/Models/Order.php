<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahin ini biar standar
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id', 
        'customer_name',
        'subtotal', 
        'tax_amount', 
        'final_total', 
        'status'
    ];

    // Relasi: Order dimiliki oleh SATU Table
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    // --- PERBAIKAN DISINI ---
    // Nama fungsi diganti dari 'items' jadi 'orderItems'
    // Biar cocok sama panggilan controller: $order->load('orderItems')
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}