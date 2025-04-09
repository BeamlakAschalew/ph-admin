<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        return Inertia::render('Admin/Products', [
            'products' => fn() => Product::with('unit')
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('product_name', 'like', "%{$search}%");
                })
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
                ->withQueryString(),
            'filters' => fn() => $request->only('search'),
            'units' => fn() => ProductUnit::distinct()->select('id', 'unit_name')->get(),
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
        try {
            $request->validate([
                'product_name' => 'required|string|max:255|min:3',
                'product_unit_id' => 'nullable|exists:product_units,id',
            ]);

            Product::create($request->only('product_name', 'product_unit_id',));

            return redirect()->back()->with('message', ['name' => 'Product created successfully.', 'type' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('message', [
                    'name' => 'Product creation failed. ' . implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error'
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product) {
        try {
            $request->validate([
                'product_name' => 'required|string|max:255|min:3',
                'product_unit_id' => 'nullable|exists:product_units,id',
            ]);

            $product->update($request->only('product_name', 'product_unit_id'));

            return redirect()->back()->with('message', ['name' => 'Product updated successfully.', 'type' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('message', [
                    'name' => 'Product update failed. ' . implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product) {
        $product->delete();

        return redirect()->back()->with('message', ['name' => 'Product deleted successfully.', 'type' => 'success']);
    }
}
