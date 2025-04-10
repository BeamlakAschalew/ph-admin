<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Consumer;
use App\Models\Subcity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConsumerAuthController extends Controller {
    public function showSignup() {
        $subcities = Subcity::all(['subcity_name', 'id']);
        return Inertia::render('Consumer/Auth/Signup', [
            'subcities' => $subcities
        ]);
    }

    public function showLogin() {
        return Inertia::render('Consumer/Auth/Login');
    }

    public function login(Request $request) {
        $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);

        $phone = Consumer::normalizePhoneNumber($request->phone);

        if (!$phone) {
            return back()->withErrors(['phone' => 'Invalid phone number format']);
        }

        if (Auth::guard('consumer')->attempt(['primary_phone' => $phone, 'password' => $request->password], true) || Auth::guard('consumer')->attempt(['secondary_phone' => $phone, 'password' => $request->password], true)) {
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
            'primary_phone' => 'required|string|max:255|unique:consumers,primary_phone',
            'secondary_phone' => 'required|string|max:255|unique:consumers,secondary_phone',
            'institution_name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'woreda' => 'required',
            'license_number' => 'required|string|max:255',
        ]);

        $primary_phone = Consumer::normalizePhoneNumber($request->primary_phone);
        $secondary_phone = Consumer::normalizePhoneNumber($request->secondary_phone);

        if (!$primary_phone) {
            return back()->withErrors(['primary_phone' => 'Invalid primary phone number format']);
        }

        if (!$secondary_phone) {
            return back()->withErrors(['primary_phone' => 'Invalid secondary phone number format']);
        }

        if (Consumer::where('primary_phone', $primary_phone)->exists()) {
            return back()->withErrors(['primary_phone' => 'The primary phone number has already been taken']);
        }

        if (Consumer::where('primary_phone', $secondary_phone)->exists()) {
            return back()->withErrors(['primary_phone' => 'The secondary phone number has already been taken']);
        }

        $consumer = Consumer::create([
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

        Auth::guard('consumer')->login($consumer, true);

        return redirect()->route('home');
    }

    public function logout() {
        Auth::guard('consumer')->logout();
        return redirect()->route('consumer.login');
    }
}
