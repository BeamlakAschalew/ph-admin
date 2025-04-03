<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConsumerController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        return Inertia::render('Consumers', [
            'consumers' => Consumer::withTrashed()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
                })
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
                ->withQueryString()
                ->through(function ($consumer) {
                    $consumer->status = $consumer->deleted_at ? 'Inactive' : 'Active';
                    return $consumer;
                }),
            'filters' => fn() => $request->only('search'),
        ]);
    }

    public function pendingIndex(Request $request) {
        return Inertia::render('PendingConsumers', [
            'consumers' => fn() => Consumer::with('subcity')
                ->where('approved', false)
                ->when($request->input('search'), function ($query, $search) {
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
        $consumer = Consumer::find($request->input('id'));
        if ($request->input('action') == 'approve') {
            $consumer->update(['approved' => true]);
            return redirect()->back()->with('message', ['name' => 'Consumer accepted.', 'type' => 'success']);
        } else {
            $consumer->delete();
            return redirect()->back()->with('message', ['name' => 'Consumer rejected.', 'type' => 'success']);
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
    public function show(Consumer $consumer) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consumer $consumer) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consumer $consumer) {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumer $consumer) {
        //
    }
}
