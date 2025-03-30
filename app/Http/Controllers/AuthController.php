<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller {
    public function showLogin() {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request) {
        $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);

        $phone = Admin::normalizePhoneNumber($request->phone); // Normalize the phone number

        if (!$phone) {
            return back()->withErrors(['phone' => 'Invalid phone number format']);
        }

        if (Auth::attempt(['phone' => $phone, 'password' => $request->password], true)) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['phone' => 'Invalid password or phone number']);
    }

    public function showRegister() {
        return Inertia::render('Auth/Signup');
    }

    public function register(Request $request) {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $phone = Admin::normalizePhoneNumber($request->phone_number);

        if (!$phone) {
            return back()->withErrors(['phone_number' => 'Invalid phone number format']);
        }

        if (Admin::where('phone', $phone)->exists()) {
            return back()->withErrors(['phone_number' => 'The phone number has already been taken']);
        }

        $user = Admin::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $phone,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
