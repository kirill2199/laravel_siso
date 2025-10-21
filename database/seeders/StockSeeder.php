<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = ['Коледино', 'Подольск', 'Электросталь', 'Казань', 'Екатеринбург'];
        $brands = ['c88460ca41fdbb82', 'brand123456789', 'brand987654321'];
        $categories = ['9f463620982b6cc9', 'category123456', 'category789012'];
        $subjects = ['bac0461335ae5efa', 'subject123456', 'subject789012'];

        for ($i = 0; $i < 100; $i++) {
            Stock::create([
                'date' => now()->format('Y-m-d'),
                'last_change_date' => now()->subDays(rand(1, 30))->setTime(
                    rand(8, 18), rand(0, 59), rand(0, 59)
                ),
                'supplier_article' => 'c88c5018511d87a8' . $i,
                'tech_size' => '66e7dff9f98764da' . $i,
                'barcode' => 212416691 + $i,
                'quantity' => rand(0, 100),
                'is_supply' => rand(0, 1) === 1,
                'is_realization' => rand(0, 1) === 1,
                'quantity_full' => rand(0, 50),
                'warehouse_name' => $warehouses[array_rand($warehouses)],
                'in_way_to_client' => rand(0, 10),
                'in_way_from_client' => rand(0, 5),
                'nm_id' => 126373749 + $i,
                'subject' => $subjects[array_rand($subjects)],
                'category' => $categories[array_rand($categories)],
                'brand' => $brands[array_rand($brands)],
                'sc_code' => 149490916 + $i,
                'price' => rand(1000, 10000) + (rand(0, 99) / 100),
                'discount' => rand(0, 50) + (rand(0, 99) / 100),
            ]);
        }
    }
}