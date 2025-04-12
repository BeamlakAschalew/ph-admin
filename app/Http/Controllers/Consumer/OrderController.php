<?php

namespace App\Http\Controllers\Consumer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomOrderItem;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller {
    public function checkout(Request $request) {
        try {
            $cart = $request->validate([
                'products' => 'array',
                'products.*.id' => 'exists:products,id',
                'products.*.quantity' => 'integer|min:1',
                'customProducts' => 'array',
                'customProducts.*.name' => 'required|string',
                'customProducts.*.quantity' => 'integer|min:1',
            ]);

            if (empty($cart['products']) && empty($cart['customProducts'])) {
                return redirect()->back()->with('message', [
                    'name' => 'Please add products to your cart.',
                    'type' => 'error'
                ]);
            }

            $order = Order::create([
                'consumer_id' => auth()->guard('consumer')->user()->id,
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
                    'product_name' => $customProduct['name'],
                    'quantity' => $customProduct['quantity'],
                ]);
            }

            return redirect()->back()->with('message', ['name' => 'Product created successfully.', 'type' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('message', [
                    'name' => 'Product order failed. ' . implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error'
                ]);
        }
    }
}
