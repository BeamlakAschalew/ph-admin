<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\AdminSecret;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller {
    public function showLogin() {
        return Inertia::render('Admin/Auth/Login');
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
        return Inertia::render('Admin/Auth/Signup');
    }

    public function register(Request $request) {
        $makeSuperAdmin = false;

        $request->validate([
            'first_name' => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
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

        if ($request->filled('superadmin_secret')) {
            $matchedSecrets = AdminSecret::all()->filter(function ($adminSecretRecord) use ($request) {
                return Hash::check($request->superadmin_secret, $adminSecretRecord->secret);
            });
            if ($matchedSecrets->isEmpty()) {
                return back()->withErrors(['superadmin_secret' => 'Invalid superadmin secret, leave the field empty if you are a regular admin']);
            }
            $makeSuperAdmin = true;
        } else {
            $makeSuperAdmin = false;
        }

        $admin = Admin::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $phone,
            'password' => Hash::make($request->password),
        ]);

        if ($makeSuperAdmin) {
            $admin->assignRole('superadmin');
        } else {
            $admin->assignRole('admin');
        }
        $admin->syncRoles([$makeSuperAdmin ? 'superadmin' : 'admin']);

        Auth::login($admin, true);

        return redirect()->route('dashboard');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
