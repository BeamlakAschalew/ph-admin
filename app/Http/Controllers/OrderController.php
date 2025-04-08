<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        // dd(Order::with('consumer')->with('items.product')->get()->toArray());
        return Inertia::render('Dashboard', [
            'orders' => Order::with([
                'consumer' => function ($query) {
                    $query->withTrashed()->with('subcity');
                },
                'items.product.unit'
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
