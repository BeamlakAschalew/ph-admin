<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller {
    public function index(Request $request) {
        return Inertia::render('Consumer/Home', [
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
