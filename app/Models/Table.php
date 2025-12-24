<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $fillable = ['table_number', 'status'];

    /**
     * Relasi: Table memiliki BANYAK Orders (pesanan dari waktu ke waktu).
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
