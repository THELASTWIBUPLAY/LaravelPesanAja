<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah dari Schema::table menjadi Schema::create
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Membuat kolom 'id' sebagai primary key auto-increment
            $table->foreignId('table_id')->constrained('tables')->onDelete('restrict'); // RELASI ke tables
            $table->string('customer_uid')->nullable(); // ID User dari Firebase/Frontend

            // Kolom Harga yang Disempurnakan (Menggantikan $table->decimal('total_price', ...))
            $table->decimal('subtotal', 10, 0)->default(0);      // Subtotal (Harga Menu + Biaya Level)
            $table->decimal('tax_amount', 8, 0)->default(0);     // PPN (10% dari Subtotal)
            $table->decimal('final_total', 10, 0)->default(0);   // Total Akhir (Subtotal + PPN)
            
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
