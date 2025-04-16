<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierHomeController extends Controller {
    public function index(Request $request) {
        return Inertia::render('Supplier/Home', [
            'products' => fn() => $request->filled('search') ? Product::with('unit')
                ->where('product_name', 'like', "%{$request->input('search')}%")
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
                ->withQueryString() : null,
            'filters' => fn() => $request->only('search'),
            'units' => fn() => ProductUnit::distinct()->select('id', 'unit_name')->get(),
        ]);
    }
}
