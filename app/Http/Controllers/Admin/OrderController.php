<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(): Response {
        // dd(Order::with('consumer')->with('items.product')->get()->toArray());
        return Inertia::render('Admin/Dashboard', [
            'orders' => Order::with([
                'consumer' => function ($query) {
                    $query->withTrashed()->with('subcity');
                },
                'items.product.unit',
                'customItems.unit',
            ])
                ->orderBy('created_at', 'desc')
                ->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order) {
        $request->validate([
            'status' => 'required|in:Pending,Completed,Cancelled',
        ]);

        $order->update($request->only('status'));

        return redirect()->back()->with('message', ['name' => 'Order status updated successfully', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order) {
        //
    }
}
