<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels_data = [
            // Level 0 - 4: Biaya Tambahan Rp 0
            ['name' => 'Level 0', 'code' => 'L0', 'description' => 'Netral, tidak pedas', 'extra_cost' => 0],
            ['name' => 'Level 1', 'code' => 'L1', 'description' => 'Pedas sedikit', 'extra_cost' => 0],
            ['name' => 'Level 2', 'code' => 'L2', 'description' => 'Pedas sedikit diatas sedikit', 'extra_cost' => 0],
            ['name' => 'Level 3', 'code' => 'L3', 'description' => 'Pedas sedang diatas sedikit', 'extra_cost' => 0],
            ['name' => 'Level 4', 'code' => 'L4', 'description' => 'Pedas banyak diatas sedikit', 'extra_cost' => 0],

            // Level 5 - 9: Biaya Tambahan Naik Rp 100 per Level
            ['name' => 'Level 5', 'code' => 'L5', 'description' => 'Pedas sedikit diatas sedang', 'extra_cost' => 100],
            ['name' => 'Level 6', 'code' => 'L6', 'description' => 'Pedas sedang diatas sedang', 'extra_cost' => 200],
            ['name' => 'Level 7', 'code' => 'L7', 'description' => 'pedas banyak diatas sedang', 'extra_cost' => 300],
            ['name' => 'Level 8', 'code' => 'L8', 'description' => 'Pedas banyak', 'extra_cost' => 400],
            ['name' => 'Level 9', 'code' => 'L9', 'description' => 'Pedas Gila', 'extra_cost' => 500],
            
            // Level Kultivator (Ranah Bawah - Ranah Atas): Biaya Tambahan Rp 0
            ['name' => 'Ranah Bawah', 'code' => 'L101', 'description' => 'Pedas Level Kultivator pemula', 'extra_cost' => 0],
            ['name' => 'Ranah Tengah', 'code' => 'L102', 'description' => 'Pedas Level Kultivator menengah', 'extra_cost' => 0],
            ['name' => 'Ranah Atas', 'code' => 'L103', 'description' => 'Pedas Level Kultivator Puncak', 'extra_cost' => 0],

            // Level Kultivator (Immortal & Heavenly Demon): Biaya Tambahan Khusus
            ['name' => 'Immortality', 'code' => 'L104', 'description' => 'Pedas Level Kultivator Immortal', 'extra_cost' => 500], // Biaya Rp 500
            ['name' => 'Heavenly Demon', 'code' => 'L105', 'description' => 'Pedas Level Kultivator Demonic Surgawi', 'extra_cost' => 1000], // Biaya Rp 1.000
        ];

        foreach ($levels_data as $level) {
            Level::create($level);
        }
    }
}