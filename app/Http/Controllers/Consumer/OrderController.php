<?php

namespace App\Http\Controllers\Consumer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller {
    public function checkout(Request $request) {
        try {
            $cart = $request->validate([
                'products' => 'array',
                'products.*.id' => 'exists:products,id',
                'products.*.quantity' => 'required|string',
                'customProducts' => 'array',
                'customProducts.*.name' => 'required|string',
                'customProducts.*.quantity' => 'required|string',
                'customProducts.*.unit' => 'nullable|integer|exists:product_units,id'
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
                    'product_unit_id' => $customProduct['unit'],
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

    public function pastOrders(Request $request) {
        $consumer = Auth::guard('consumer')->user();
        $orders = \App\Models\Order::with(['items.product.unit', 'customItems.unit'])
            ->where('consumer_id', $consumer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return \Inertia\Inertia::render('Consumer/PastOrders', [
            'orders' => $orders,
        ]);
    }
}
