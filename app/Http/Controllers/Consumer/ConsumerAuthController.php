<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Request;
use Inertia\Inertia;

class ConsumerAuthController extends Controller {
    public function showSignup() {
        return Inertia::render('Consumer/Auth/Signup');
    }
}
