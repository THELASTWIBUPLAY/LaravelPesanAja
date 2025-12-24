<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan', 'description' => 'Menu mie utama seperti Mie Nggak Cuan, Mie HipHipHore'],
            ['name' => 'Dimsum & Side Dish', 'description' => 'Menu pendamping seperti U dan G Keju, MyPangsit'],
            ['name' => 'Minuman', 'description' => 'Minuman segar seperti Es Kelapa Tua, Es Sam Purasun'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}