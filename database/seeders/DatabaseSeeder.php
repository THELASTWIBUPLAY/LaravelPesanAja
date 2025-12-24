<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Pastikan LevelSeeder dijalankan sebelum AdminUserSeeder
            LevelSeeder::class, // Mengisi data Level pedas/akses

            CategorySeeder::class,
            TableSeeder::class,
            MenuSeeder::class,
        ]);
    }
}