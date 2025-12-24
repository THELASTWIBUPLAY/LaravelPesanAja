<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Category;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil ID Kategori
        // Revisi: Mengambil kategori berdasarkan nama yang benar yang dibuat di CategorySeeder.php
        $makananCategory = Category::where('name', 'Makanan')->first(); 
        $sideDishCategory = Category::where('name', 'Dimsum & Side Dish')->first();
        $minumanCategory = Category::where('name', 'Minuman')->first();

        // Pastikan kategori ditemukan
        if ($makananCategory) {
            // --- A. MAKANAN UTAMA (Mie & Nasi) ---
            
            // Menu Pedas (Butuh Level)
            Menu::create([
                'category_id' => $makananCategory->id,
                'name' => 'Mie Nggak Cuan',
                'description' => 'Mie pedas dengan sensasi bumbu rahasia yang bikin nagih, wajib pilih level!',
                'price' => 12000,
                'has_level' => true, 
            ]);
            Menu::create([
                'category_id' => $makananCategory->id,
                'name' => 'Mie HipHipHore',
                'description' => 'Mie pedas manis dengan campuran cabai rawit dan gula aren.',
                'price' => 12000,
                'has_level' => true,
            ]);

            // Menu Spesial Kultivator
            Menu::create([
                'category_id' => $makananCategory->id,
                'name' => 'Mie Kultivator',
                'description' => 'Porsi ganda dengan topping lengkap. Hanya untuk para kultivator sejati, wajib pilih level tinggi!',
                'price' => 25000, // Harga lebih mahal karena porsi besar
                'has_level' => true, 
            ]);
            
            // Menu Tanpa Level (Biasa)
            Menu::create([
                'category_id' => $makananCategory->id,
                'name' => 'Mie Kicep',
                'description' => 'Mie gurih tanpa pedas, aman untuk perut dan dompet.',
                'price' => 10000,
                'has_level' => false,
            ]);
            Menu::create([
                'category_id' => $makananCategory->id,
                'name' => 'Nasi Nggak Rugi',
                'description' => 'Nasi dengan lauk ayam goreng dan sambal matah.',
                'price' => 18000,
                'has_level' => false,
            ]);
        }

        if ($sideDishCategory) {
            // --- B. DIMSUM & SIDE DISH ---
            
            Menu::create([
                'category_id' => $sideDishCategory->id,
                'name' => 'U dan G Keju',
                'description' => 'Udang keju, renyah di luar, keju lumer di dalam.',
                'price' => 14000,
                'has_level' => false,
            ]);
            Menu::create([
                'category_id' => $sideDishCategory->id,
                'name' => 'MyPangsit',
                'description' => 'Pangsit goreng dengan isian ayam dan sayuran.',
                'price' => 11000,
                'has_level' => false,
            ]);
            Menu::create([
                'category_id' => $sideDishCategory->id,
                'name' => 'Si Kribo',
                'description' => 'Siomay yang digoreng hingga krispi seperti rambut kribo.',
                'price' => 13000,
                'has_level' => false,
            ]);
            Menu::create([
                'category_id' => $sideDishCategory->id,
                'name' => 'Tahu Garing',
                'description' => 'Tahu bulat digoreng garing, dicocol saus kecap pedas.',
                'price' => 9000,
                'has_level' => false,
            ]);
        }

        if ($minumanCategory) {
            // --- C. MINUMAN ---
            
            Menu::create([
                'category_id' => $minumanCategory->id,
                'name' => 'Es Kelapa Tua',
                'description' => 'Es kelapa muda dengan gula merah asli.',
                'price' => 8000,
                'has_level' => false,
            ]);
            Menu::create([
                'category_id' => $minumanCategory->id,
                'name' => 'Es Sam Purasun',
                'description' => 'Es campur buah dan biji-bijian, sangat segar.',
                'price' => 10000,
                'has_level' => false,
            ]);
            Menu::create([
                'category_id' => $minumanCategory->id,
                'name' => 'Es Semut Berbaris',
                'description' => 'Minuman cokelat dengan butiran es yang menyerupai semut.',
                'price' => 10000,
                'has_level' => false,
            ]);
            Menu::create([
                'category_id' => $minumanCategory->id,
                'name' => 'Lemon Es',
                'description' => 'Air perasan lemon murni dengan es.',
                'price' => 7000,
                'has_level' => false,
            ]);
        }
    }
}