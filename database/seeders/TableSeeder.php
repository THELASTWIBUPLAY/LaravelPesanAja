<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh membuat 10 meja
        for ($i = 1; $i <= 10; $i++) {
            Table::create([
                'table_number' => str_pad($i, 2, '0', STR_PAD_LEFT), // Hasil: T-01, T-02, ..., T-10
                'status' => 'available',
            ]);
        }
    }
}