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
            'orders' => Order::with('consumer.subcity')
                ->with(['items.product.unit'])
                ->paginate(10),
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order) {
        //
    }
}
