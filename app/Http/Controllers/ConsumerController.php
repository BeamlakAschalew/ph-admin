<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Models\Subcity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ConsumerController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        return Inertia::render('Admin/Consumers', [
            'consumers' => Consumer::withTrashed()->with('subcity')
                ->where('approved', true)
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
            'subcities' => fn() => Subcity::all(),
        ]);
    }

    public function pendingIndex(Request $request) {
        return Inertia::render('Admin/PendingConsumers', [
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
        $consumer->update(['approved' => true]);
        if ($request->input('action') == 'approve') {
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
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'special_place' => 'required|string|max:255',
                'subcity_id' => 'required|exists:subcities,id',
                'primary_phone' => 'required|string|max:255|unique:consumers,primary_phone',
                'secondary_phone' => 'required|string|max:255|unique:consumers,secondary_phone',
                'institution_name' => 'required|string|max:255',
                'password' => 'required|string|min:6',
                'woreda' => 'required',
                'license_number' => 'required|string|max:255',
            ]);

            $primaryPhone = Consumer::normalizePhoneNumber($request->input('primary_phone'));
            $secondaryPhone = Consumer::normalizePhoneNumber($request->input('secondary_phone'));
            if (!$primaryPhone || !$secondaryPhone) {
                return redirect()->back()->with('message', ['name' => 'Invalid phone number format', 'type' => 'error']);
            }
            $request->merge([
                'primary_phone' => $primaryPhone,
                'secondary_phone' => $secondaryPhone,
            ]);

            if ($request->input('primary_phone') == $request->input('secondary_phone')) {
                return redirect()->back()->with('message', ['name' => 'Primary and secondary phone numbers cannot be the same', 'type' => 'error']);
            }

            $consumer = Consumer::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'special_place' => $request->input('special_place'),
                'subcity_id' => $request->input('subcity_id'),
                'primary_phone' => $request->input('primary_phone'),
                'secondary_phone' => $request->input('secondary_phone'),
                'woreda' => $request->input('woreda'),
                'password' => Hash::make($request->input('password')),
                'institution_name' => $request->input('institution_name'),
                'license_number' => $request->input('license_number'),
            ]);

            if ($consumer) {
                return redirect()->back()->with('message', ['name' => 'Consumer created successfully.', 'type' => 'success']);
            } else {
                return redirect()->back()->with('message', ['name' => 'Consumer creation failed.', 'type' => 'error']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('message', [
                    'name' => 'Consumer creation failed. ' . implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error'
                ]);
        }
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
    public function update(Request $request, $id) {
        $consmuer = Consumer::withTrashed()->findOrFail($id);
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'special_place' => 'required|string|max:255',
                'subcity_id' => 'required|exists:subcities,id',
                'primary_phone' => 'required|string|max:255|unique:consumers,primary_phone,' . $consmuer->id,
                'secondary_phone' => 'required|string|max:255|unique:consumers,secondary_phone,' . $consmuer->id,
                'institution_name' => 'required|string|max:255',
                'password' => 'nullable|string|min:6',
                'woreda' => 'required',
                'status' => 'required|in:Rejected,Approved',
            ]);

            $primaryPhone = Consumer::normalizePhoneNumber($request->input('primary_phone'));
            $secondaryPhone = Consumer::normalizePhoneNumber($request->input('secondary_phone'));
            if (!$primaryPhone || !$secondaryPhone) {
                return redirect()->back()->with('message', ['name' => 'Invalid phone number format', 'type' => 'error']);
            }
            $request->merge([
                'primary_phone' => $primaryPhone,
                'secondary_phone' => $secondaryPhone,
            ]);

            if ($request->input('primary_phone') == $request->input('secondary_phone')) {
                return redirect()->back()->with('message', ['name' => 'Primary and secondary phone numbers cannot be the same', 'type' => 'error']);
            }

            $data = $request->only(
                'first_name',
                'last_name',
                'special_place',
                'subcity_id',
                'primary_phone',
                'secondary_phone',
                'woreda',
                'password',
                'institution_name'
            );

            if ($request->input('status') === 'Rejected') {
                $data['deleted_at'] = now();
            } else {
                $data['deleted_at'] = null;
            }

            $consmuer->update($data);

            return redirect()->back()->with('message', ['name' => 'Consumer updated.', 'type' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('message', [
                    'name' => 'Consumer update failed. ' . implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumer $consumer) {
        $consumer->forceDelete();
        return redirect()->back()->with('message', ['name' => 'Consumer deleted.', 'type' => 'success']);
    }
}
