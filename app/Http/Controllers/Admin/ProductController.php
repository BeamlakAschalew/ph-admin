<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return inertia('Admin/Products', [
            'products' => fn () => Product::with('unit')
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('product_name', 'like', "%{$search}%");
                })
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
                ->withQueryString(),
            'filters' => fn () => $request->only('search'),
            'units' => fn () => ProductUnit::distinct()->select('id', 'unit_name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_name' => 'required|string|max:255|min:3|unique:products,product_name',
                'product_unit_id' => 'nullable|exists:product_units,id',
            ]);

            Product::create($request->only('product_name', 'product_unit_id'));

            return redirect()->back()->with('message', ['name' => 'Product created successfully.', 'type' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('message', [
                    'name' => 'Product creation failed. '.implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error',
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
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
                    'name' => 'Product update failed. '.implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error',
                ]);
        }
    }

    public function importFromExcel(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ]);

            $excelData = Excel::toArray([], $request->file('file'))[0];

            // Prepare products from Excel, normalizing product_name for comparison
            $productsFromExcel = collect(array_slice($excelData, 1))
                ->filter(fn ($row) => ! empty(trim($row[0]))) // Filter out rows with empty product names
                ->map(function ($row) {
                    return [
                        'original_product_name' => trim($row[0]), // Keep original for insertion
                        'normalized_product_name' => strtolower(trim($row[0])), // For case-insensitive comparison
                        'product_unit_id' => isset($row[1]) ? $row[1] : null,
                    ];
                });

            if ($productsFromExcel->isEmpty()) {
                return redirect()->back()->with('message', ['name' => 'No products found in the file to import.', 'type' => 'error']);
            }

            // Get existing product names from DB, normalized for comparison
            $existingNormalizedProductNames = Product::pluck('product_name')->map(function ($name) {
                return strtolower(trim($name));
            })->flip(); // flip() to use isset for faster lookups (O(1) on average)

            // Filter out products that already exist (case-insensitive comparison)
            // and prepare them for insertion with original names and timestamps
            $productsToInsertData = $productsFromExcel->filter(function ($productData) use ($existingNormalizedProductNames) {
                return ! isset($existingNormalizedProductNames[$productData['normalized_product_name']]);
            })
                ->map(function ($productData) {
                    return [
                        'product_name' => $productData['original_product_name'],
                        'product_unit_id' => $productData['product_unit_id'],
                    ];
                })->all();

            if (! empty($productsToInsertData)) {
                Product::insert($productsToInsertData);
                $message = count($productsToInsertData).' products imported successfully.';
            } else {
                $message = 'No new products to import. All products in the file already exist.';
            }

            return redirect()->back()->with('message', ['name' => $message, 'type' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('message', [
                    'name' => 'Product import failed. '.implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error',
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->back()->with('message', ['name' => 'Product deleted successfully.', 'type' => 'success']);
    }
}
