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
            'subcities' => fn() => \App\Models\Subcity::all(['id', 'subcity_name']),
        ]);
    }
}
