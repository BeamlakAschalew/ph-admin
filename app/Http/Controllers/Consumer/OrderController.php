<?php

namespace App\Http\Controllers\Consumer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomOrderItem;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller {
    public function checkout(Request $request) {
        $cart = $request->validate([
            'products' => 'array',
            'products.*.id' => 'exists:products,id',
            'products.*.quantity' => 'integer|min:1',
            'customProducts' => 'array',
            'customProducts.*.product_name' => 'required|string',
            'customProducts.*.quantity' => 'integer|min:1',
        ]);

        $order = Order::create([
            'consumer_id' => auth()->guard('consumer')->id,
            'status' => 'Pending'
        ]);

        foreach ($cart['products'] as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
            ]);
        }

        foreach ($cart['customProducts'] as $customProduct) {
            CustomOrderItem::create([
                'order_id' => $order->id,
                'product_name' => $customProduct['product_name'],
                'quantity' => $customProduct['quantity'],
            ]);
        }

        return redirect()->back()->with('message', ['name' => 'Product created successfully.', 'type' => 'success']);
    }
}
