<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Subcity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SupplierAuthController extends Controller {
    public function showSignup() {
        $subcities = Subcity::all(['subcity_name', 'id']);
        return Inertia::render('Supplier/Auth/Signup', [
            'subcities' => $subcities
        ]);
    }

    public function showLogin() {
        return Inertia::render('Supplier/Auth/Login');
    }

    public function login(Request $request) {
        $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);

        $phone = Supplier::normalizePhoneNumber($request->phone);

        if (!$phone) {
            return back()->withErrors(['phone' => 'Invalid phone number format']);
        }

        if (Auth::guard('supplier')->attempt(['primary_phone' => $phone, 'password' => $request->password], true) || Auth::guard('supplier')->attempt(['secondary_phone' => $phone, 'password' => $request->password], true)) {
            return redirect()->route('home');
        }

        return back()->withErrors(['phone' => 'Invalid password or phone number']);
    }

    public function register(Request $request) {
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
        ]);

        $primary_phone = Supplier::normalizePhoneNumber($request->primary_phone);
        $secondary_phone = Supplier::normalizePhoneNumber($request->secondary_phone);

        if (!$primary_phone) {
            return back()->withErrors(['primary_phone' => 'Invalid primary phone number format']);
        }

        if (!$secondary_phone) {
            return back()->withErrors(['primary_phone' => 'Invalid secondary phone number format']);
        }

        if (Supplier::where('primary_phone', $primary_phone)->exists()) {
            return back()->withErrors(['primary_phone' => 'The primary phone number has already been taken']);
        }

        if (Supplier::where('primary_phone', $secondary_phone)->exists()) {
            return back()->withErrors(['primary_phone' => 'The secondary phone number has already been taken']);
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
        ]);

        Auth::guard('supplier')->login($supplier, true);

        return redirect()->route('supplier.home');
    }

    public function logout() {
        Auth::guard('supplier')->logout();
        return redirect()->route('supplier.login');
    }
}
