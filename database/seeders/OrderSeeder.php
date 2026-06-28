<?php

namespace Database\Seeders;

use App\Models\CustomOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates pharmacy orders with associated order items and custom items.
     * Each order has 2-5 items, and the total_amount is auto-calculated.
     */
    public function run(): void
    {
        // Create 25 orders distributed across consumers
        $orders = Order::factory()->count(25)->create();

        $totalOrderItems = 0;
        $totalCustomItems = 0;

        foreach ($orders as $order) {
            // Each order gets 2-5 regular order items
            $itemCount = rand(2, 5);
            $items = OrderItem::factory()->count($itemCount)->create([
                'order_id' => $order->id,
            ]);
            $totalOrderItems += $itemCount;

            // Each order gets 0-2 custom items
            $customCount = rand(0, 2);
            if ($customCount > 0) {
                CustomOrderItem::factory()->count($customCount)->create([
                    'order_id' => $order->id,
                ]);
                $totalCustomItems += $customCount;
            }

            // Calculate a realistic total (5-500 birr per item)
            $total = 0;
            foreach ($items as $item) {
                $unitPrice = rand(5, 500);
                $total += $unitPrice * (int) $item->quantity;
            }
            $order->update(['total_amount' => $total]);
        }

        // Update status distribution
        $orders->take(15)->each->update(['status' => 'Completed']);
        $orders->skip(15)->take(7)->each->update(['status' => 'Pending']);
        $orders->skip(22)->each->update(['status' => 'Cancelled']);

        $this->command?->info('✓ Created '.Order::count().' orders with '.$totalOrderItems.' items and '.$totalCustomItems.' custom items');
    }
}
