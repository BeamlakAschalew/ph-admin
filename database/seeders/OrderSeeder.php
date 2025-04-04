<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $orders = Order::factory()->count(20)->create();
        OrderItem::factory()->count(60)->state(function () use ($orders) {
            return [
                'order_id' => $orders->random()->id,
            ];
        })->create();
    }
}
