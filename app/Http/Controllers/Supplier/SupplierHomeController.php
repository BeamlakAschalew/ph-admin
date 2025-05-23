<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierHomeController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Supplier/Home', [
            'subcities' => fn () => \App\Models\Subcity::all(['id', 'subcity_name']),
        ]);
    }
}
