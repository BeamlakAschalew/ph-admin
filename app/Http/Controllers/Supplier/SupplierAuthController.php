<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Subcity;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierAuthController extends Controller
{
    public function showSignup()
    {
        $subcities = Subcity::all(['subcity_name', 'id']);

        return inertia('Supplier/Auth/Signup', [
            'subcities' => $subcities,
        ]);
    }

    public function showLogin()
    {
        return inertia('Supplier/Auth/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $phone = Supplier::normalizePhoneNumber($request->phone);

        if (! $phone) {
            return back()->withErrors(['phone' => 'Invalid phone number format']);
        }

        if (Auth::guard('supplier')->attempt(['primary_phone' => $phone, 'password' => $request->password], true) || Auth::guard('supplier')->attempt(['secondary_phone' => $phone, 'password' => $request->password], true)) {
            return redirect()->route('supplier.home');
        }

        return back()->withErrors(['phone' => 'Invalid password or phone number']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'special_place' => 'required|string|max:255',
            'subcity_id' => 'required|exists:subcities,id',
            'primary_phone' => 'required|string|max:255|unique:suppliers,primary_phone',
            'secondary_phone' => 'required|string|max:255|unique:suppliers,secondary_phone',
            'institution_name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'woreda' => 'required',
            'license_number' => 'required|string|max:255',
            'tin_number' => 'required|string|max:255',
        ]);

        $primary_phone = Supplier::normalizePhoneNumber($request->primary_phone);
        $secondary_phone = Supplier::normalizePhoneNumber($request->secondary_phone);

        if (! $primary_phone) {
            return back()->withErrors(['primary_phone' => 'Invalid primary phone number format']);
        }

        if (! $secondary_phone) {
            return back()->withErrors(['primary_phone' => 'Invalid secondary phone number format']);
        }

        if (Supplier::withTrashed()->where('primary_phone', $primary_phone)->exists()) {
            return back()->withErrors(['primary_phone' => 'The primary phone number has already been taken']);
        }

        if (Supplier::withTrashed()->where('secondary_phone', $secondary_phone)->exists()) {
            return back()->withErrors(['secondary_phone' => 'The secondary phone number has already been taken']);
        }

        $supplier = Supplier::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'special_place' => $request->special_place,
            'subcity_id' => $request->subcity_id,
            'primary_phone' => $primary_phone,
            'secondary_phone' => $secondary_phone,
            'institution_name' => $request->institution_name,
            'password' => $request->password,
            'woreda' => $request->woreda,
            'license_number' => $request->license_number,
            'tin_number' => $request->tin_number,
        ]);

        Auth::guard('supplier')->login($supplier, true);

        return redirect()->route('supplier.home');
    }

    public function logout()
    {
        Auth::guard('supplier')->logout();

        return redirect()->route('supplier.login');
    }

    public function updateProfile(Request $request)
    {
        try {
            $supplier = Auth::guard('supplier')->user();
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'special_place' => 'required|string|max:255',
                'subcity_id' => 'required|exists:subcities,id',
                'primary_phone' => 'required|string|max:255|unique:suppliers,primary_phone,'.$supplier->id,
                'secondary_phone' => 'required|string|max:255|unique:suppliers,secondary_phone,'.$supplier->id,
                'institution_name' => 'required|string|max:255',
                'password' => 'nullable|string|min:6|confirmed',
                'woreda' => 'required',
            ]);

            $primaryPhone = Supplier::normalizePhoneNumber($request->input('primary_phone'));
            $secondaryPhone = Supplier::normalizePhoneNumber($request->input('secondary_phone'));
            if (! $primaryPhone || ! $secondaryPhone) {
                return redirect()->back()->with('message', ['name' => 'Invalid phone number format', 'type' => 'error']);
            }

            if (Supplier::where('primary_phone', $primaryPhone)->where('id', '!=', $supplier->id)->exists()) {
                return redirect()->back()->with('message', ['name' => 'Primary phone number already taken', 'type' => 'error']);
            }

            if (Supplier::where('secondary_phone', $secondaryPhone)->where('id', '!=', $supplier->id)->exists()) {
                return redirect()->back()->with('message', ['name' => 'Secondary phone number already taken', 'type' => 'error']);
            }

            $request->merge([
                'primary_phone' => $primaryPhone,
                'secondary_phone' => $secondaryPhone,
            ]);

            if ($primaryPhone === $secondaryPhone) {
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
                'institution_name'
            );

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->input('password'));
            }

            $supplier->update($data);

            return redirect()->back()->with('message', ['name' => 'Supplier profile updated.', 'type' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('message', [
                    'name' => 'Supplier profile update failed. '.implode(' ', array_map(function ($messages) {
                        return implode(' ', $messages);
                    }, $e->errors())),
                    'type' => 'error',
                ]);
        }
    }
}
