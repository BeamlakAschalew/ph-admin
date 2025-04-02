<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Inertia::render('Suppliers');
    }

    public function pendingIndex(Request $request) {
        return Inertia::render('PendingSuppliers', [
            'suppliers' => fn() => Supplier::with('subcity')->where('approved', false)->when($request->input('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('institution_name', 'like', "%{$search}%");
                });
            })
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
                ->withQueryString(),

        ]);
    }

    function approveOrReject(Request $request) {
        $consumer = Supplier::find($request->input('id'));
        if ($request->input('action') == 'approve') {
            $consumer->update(['approved' => true]);
            return redirect()->back()->with('message', ['name' => 'Supplier approved.', 'type' => 'success']);
        } else {
            $consumer->delete();
            return redirect()->back()->with('message', ['name' => 'Supplier rejected.', 'type' => 'success']);
        }
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
    public function show(Supplier $supplier) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier) {
        //
    }
}
