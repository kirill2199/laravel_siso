<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            'Екатеринбург - Перспективный 12',
            'Коледино', 
            'Подольск',
            'Электросталь',
            'Казань',
            'Новосибирск'
        ];

        for ($i = 0; $i < 50; $i++) {
            Income::create([
                'income_id' => 33642875 + $i,
                'number' => '',
                'date' => now()->subDays(rand(0, 30))->format('Y-m-d'),
                'last_change_date' => now()->subDays(rand(0, 30))->setTime(
                    rand(8, 18), rand(0, 59), rand(0, 59)
                ),
                'supplier_article' => '4a766237b2eaeca1' . $i,
                'tech_size' => '66e7dff9f98764da' . $i,
                'barcode' => 121537971 + $i,
                'quantity' => rand(1, 10),
                'total_price' => rand(0, 1000) + (rand(0, 99) / 100),
                'date_close' => now()->subDays(rand(0, 30))->format('Y-m-d'),
                'warehouse_name' => $warehouses[array_rand($warehouses)],
                'nm_id' => 784557217 + $i,
            ]);
        }
    }
}