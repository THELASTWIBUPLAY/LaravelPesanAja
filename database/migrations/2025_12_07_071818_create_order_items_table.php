<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // RELASI ke orders
            $table->foreignId('menu_id')->constrained('menus')->onDelete('restrict'); // RELASI ke menus
            $table->foreignId('level_id')->nullable()->constrained('levels')->onDelete('set null'); // RELASI ke levels (opsional)

            $table->unsignedSmallInteger('quantity'); // Jumlah item
            $table->decimal('unit_price', 8, 0); // Harga saat dipesan (penting untuk histori)
            $table->text('notes')->nullable(); // Tambahan deskripsi untuk setiap pesanan (Misal: "Tidak pakai bawang")

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};