<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 1. Tambahkan kolom baru untuk Nama Pelanggan
            $table->string('customer_name')->after('table_id'); 
            
            // 2. Hapus kolom customer_uid karena sudah tidak relevan
            // Atau jika migration create_orders_table belum dijalankan, 
            // biarkan saja customer_uid menjadi nullable (atau hapus dari file create_orders_table)
            // Jika sudah terlanjur dibuat, kita bisa menghapusnya:
            $table->dropColumn('customer_uid'); 
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('customer_name');
            // Jika Anda menghapus customer_uid di up(), kembalikan di down()
            $table->string('customer_uid')->nullable(); 
        });
    }
};