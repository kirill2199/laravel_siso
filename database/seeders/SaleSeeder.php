<?php

namespace Database\Seeders;

use App\Models\Sale;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = ['Чашниково', 'Коледино', 'Подольск', 'Электросталь'];
        $regions = ['Забайкальский край', 'Московская область', 'Ленинградская область', 'Краснодарский край'];
        $oblasts = ['Дальневосточный федеральный округ', 'Центральный федеральный округ', 'Северо-Западный федеральный округ'];
        $brands = ['5620e3f5fde551e5', 'brand123456789', 'brand987654321'];
        $categories = ['9f463620982b6cc9', 'category123456', 'category789012'];
        $subjects = ['a988908c6c3cca9c', 'subject123456', 'subject789012'];

        for ($i = 0; $i < 200; $i++) {
            Sale::create([
                'g_number' => '9639664014970632867' . $i,
                'date' => now()->subDays(rand(0, 30))->format('Y-m-d'),
                'last_change_date' => now()->subDays(rand(0, 30))->setTime(
                    rand(8, 18), rand(0, 59), rand(0, 59)
                ),
                'supplier_article' => '854d5376e6c7d5a5' . $i,
                'tech_size' => '66e7dff9f98764da' . $i,
                'barcode' => 16857357 + $i,
                'total_price' => rand(1000, 5000) + (rand(0, 99) / 100),
                'discount_percent' => rand(10, 60),
                'is_supply' => rand(0, 1) === 1,
                'is_realization' => rand(0, 1) === 1,
                'promo_code_discount' => rand(0, 1) ? rand(50, 200) + (rand(0, 99) / 100) : null,
                'warehouse_name' => $warehouses[array_rand($warehouses)],
                'country_name' => 'Россия',
                'oblast_okrug_name' => $oblasts[array_rand($oblasts)],
                'region_name' => $regions[array_rand($regions)],
                'income_id' => rand(100000, 999999),
                'sale_id' => 'S' . (19446656048 + $i),
                'odid' => rand(0, 1) ? rand(1000000, 9999999) : null,
                'spp' => rand(5, 25),
                'for_pay' => rand(300, 2000) + (rand(0, 99) / 100),
                'finished_price' => rand(400, 2500) + (rand(0, 99) / 100),
                'price_with_disc' => rand(500, 3000) + (rand(0, 99) / 100),
                'nm_id' => 861474145 + $i,
                'subject' => $subjects[array_rand($subjects)],
                'category' => $categories[array_rand($categories)],
                'brand' => $brands[array_rand($brands)],
                'is_storno' => rand(0, 1) ? (rand(0, 1) === 1) : null,
            ]);
        }
    }
}