<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AdminController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {

        return Inertia::render('Admins', [
            'admins' => Admin::withTrashed()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
                })
                ->orderBy('updated_at', 'desc')
                ->paginate(10)
                ->withQueryString()
                ->through(function ($admin) {
                    $admin->status = $admin->deleted_at ? 'Inactive' : 'Active';
                    $admin->role = $admin->hasRole('superadmin') ? 'Super Admin' : 'Regular Admin';
                    return $admin;
                }),
            'filters' => fn() => $request->only('search'),
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
                'first_name' => 'required|string|min:3|max:255',
                'last_name' => 'required|string|min:3|max:255',
                'phone_number' => 'required|unique:admins,phone',
                'password' => 'required|min:6'
            ]);



            $phone = Admin::normalizePhoneNumber($request->phone_number);



            if (!$phone) {
                return redirect()->back()->with('message', ['name' => 'Invalid phone number format', 'type' => 'error']);
            }

            if (Admin::where('phone', $phone)->exists()) {
                return redirect()->back()->with('message', ['name' => 'The phone number has already been taken', 'type' => 'error']);
            }

            $admin = Admin::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $phone,
                'password' => Hash::make($request->password),
            ]);
            $admin->assignRole('admin');
            $admin->syncRoles(['admin']);
            return redirect()->back()->with('message', ['name' => 'Admin created successfully', 'type' => 'success']);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with('message', [
                    'name' => 'Admin creation failed. ' . implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error'
                ]);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with('message', ['name' => 'An error occurred while creating the admin.', 'type' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        $admin = Admin::withTrashed()->findOrFail($id);
        try {

            $request->validate([
                'first_name'   => 'required|string|min:3|max:255',
                'last_name'    => 'required|string|min:3|max:255',
                'phone_number' => 'required',
                'password'     => 'nullable|string|min:6',
                'status'       => 'required|boolean'
            ]);

            $phone = Admin::normalizePhoneNumber($request->phone_number);

            if (!$phone) {
                return redirect()->back()->with('message', ['name' => 'Invalid phone number format', 'type' => 'error']);
            }

            if ($phone !== $admin->phone && Admin::where('phone', $phone)->exists()) {
                return redirect()->back()->with('message', ['name' => 'The phone number has already been taken', 'type' => 'error']);
            }

            $fields = $request->only(['first_name', 'last_name', 'password', 'status']);
            $fields['phone'] = $phone;

            if (!empty($fields['password'])) {
                $fields['password'] = Hash::make($fields['password']);
            } else {
                unset($fields['password']);
            }

            $admin->update($fields);

            if ($fields['status'] === false) {
                $admin->delete();
            } else {
                if ($admin->trashed()) {
                    $admin->restore();
                }
            }

            return redirect()->back()->with('message', ['name' => 'Admin updated successfully.', 'type' => 'success']);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with('message', [
                    'name' => 'Admin update failed. ' . implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $admin = Admin::withTrashed()->findOrFail($id);
        try {
            $admin->forceDelete();
            return redirect()->back()->with('message', ['name' => 'Admin permanently deleted successfully.', 'type' => 'success']);
        } catch (Exception $e) {
            return redirect()->back()->with('message', ['name' => 'An error occurred while permanently deleting the admin.', 'type' => 'error']);
        }
    }
}
