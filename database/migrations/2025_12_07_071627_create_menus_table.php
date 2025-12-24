<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // RELASI ke categories
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 0); // Harga menu (contoh: 8 digit total, 0 di belakang koma)
            $table->boolean('has_level')->default(false); // Flag apakah menu ini butuh level (misal: mie gacoan)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};