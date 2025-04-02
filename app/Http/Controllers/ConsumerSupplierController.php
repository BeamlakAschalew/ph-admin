<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConsumerSupplierController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Inertia::render('PendingUsers');
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
    public function show($type, $id) {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass) {
            abort(404, 'Resource not found');
        }
        $item = $modelClass::findOrFail($id);
        // Return or view the item as needed
        return view("{$type}.show", compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($type, $id) {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass) {
            abort(404, 'Resource not found');
        }
        $item = $modelClass::findOrFail($id);
        // Return a view for editing the resource
        return view("{$type}.edit", compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $type, $id) {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass) {
            abort(404, 'Resource not found');
        }
        $item = $modelClass::findOrFail($id);

        // Validate the request. Adjust the rules as needed.
        $validatedData = $request->validate([
            // Example validation rules; customize per model requirements.
            'name'  => 'required|string|max:255',
            'email' => 'sometimes|email',
            // Add other fields as needed.
        ]);

        $item->update($validatedData);

        // Redirect back or to some route with a success message.
        return redirect()->back()->with('success', ucfirst($type) . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($type, $id) {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass) {
            abort(404, 'Resource not found');
        }
        $item = $modelClass::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', ucfirst($type) . ' deleted successfully.');
    }

    /**
     * Get the fully-qualified Model class based on resource type.
     */
    private function getModelClass($type) {
        $resources = [
            'consumer' => Consumer::class,
            'supplier' => Supplier::class,
        ];

        $type = strtolower($type);
        return $resources[$type] ?? null;
    }
}
