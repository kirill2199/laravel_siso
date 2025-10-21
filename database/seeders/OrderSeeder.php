<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = ['Коледино', 'Подольск', 'Электросталь', 'Казань', 'Новосибирск'];
        $oblasts = ['Московская область', 'Ленинградская область', 'Свердловская область', 'Республика Татарстан'];
        $brands = ['brand123456789', 'brand987654321', 'brand555555555'];
        $categories = ['category123456', 'category789012', 'category555555'];
        $subjects = ['subject123456', 'subject789012', 'subject555555'];
        $statuses = ['awaiting_registration', 'acceptance_in_progress', 'awaiting_approve', 'awaiting_packaging', 'awaiting_deliver'];

        for ($i = 0; $i < 150; $i++) {
            $isCancel = rand(0, 10) === 1; // 10% chance of cancellation
            
            Order::create([
                'date' => now()->subDays(rand(0, 60))->format('Y-m-d'),
                'last_change_date' => now()->subDays(rand(0, 60))->setTime(
                    rand(8, 18), rand(0, 59), rand(0, 59)
                ),
                'supplier_article' => 'ART' . rand(100000, 999999),
                'tech_size' => 'SIZE' . rand(1, 10),
                'barcode' => 2000000000000 + $i,
                'total_price' => rand(500, 5000) + (rand(0, 99) / 100),
                'discount_percent' => rand(0, 30),
                'warehouse_name' => $warehouses[array_rand($warehouses)],
                'oblast' => $oblasts[array_rand($oblasts)],
                'income_id' => rand(100000, 999999),
                'odid' => 'OD' . (1000000000 + $i),
                'nm_id' => 100000000 + $i,
                'subject' => $subjects[array_rand($subjects)],
                'category' => $categories[array_rand($categories)],
                'brand' => $brands[array_rand($brands)],
                'is_cancel' => $isCancel,
                'cancel_dt' => $isCancel ? now()->subDays(rand(0, 30)) : null,
                'g_number' => 'G' . (10000000000000000000 + $i),
                'sticker' => 'ST' . rand(1000000000, 9999999999),
                'srid' => 'SR' . rand(1000000000, 9999999999),
                'quantity' => rand(1, 5),
                'finished_price' => rand(400, 4500) + (rand(0, 99) / 100),
                'price_with_disc' => rand(450, 4800) + (rand(0, 99) / 100),
                'is_supply' => rand(0, 1) === 1,
                'is_realization' => rand(0, 1) === 1,
                'spp' => rand(5, 15),
                'order_type' => 'Клиентский',
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }
}