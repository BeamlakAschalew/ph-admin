<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required'
        ]);

        $phone = $request->phone;

        if (!$phone) {
            return back()->withErrors(['phone' => 'Invalid phone number format']);
        }

        if (Auth::attempt(['phone' => $phone, 'password' => $request->password])) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['phone' => 'Invalid credentials']);
    }

    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|unique:users,phone',
            'password' => 'required|min:6|confirmed'
        ]);

        $phone = $request->phone;

        if (!$phone) {
            return back()->withErrors(['phone' => 'Invalid phone number format']);
        }

        $user = Admin::create([
            'name' => $request->name,
            'phone' => $phone,
            'password' => $request->password,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
