<?php

use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/signup', [AuthController::class, 'showRegister'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/pending-consumers', [ConsumerController::class, 'pendingIndex'])->name('pending-consumers.index');
    Route::get('/pending-suppliers', [SupplierController::class, 'pendingIndex'])->name('pending-suppliers.index');

    Route::post('/pending-consumers', [ConsumerController::class, 'approveOrReject'])->name('approve-consumer');
    Route::post('/pending-suppliers', [SupplierController::class, 'approveOrReject'])->name('approve-supplier');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});



Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
    Route::get('/admins', [AdminController::class, 'index']);
    Route::get('/consumers', [ConsumerController::class, 'index'])->name('consumers.index');
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');



    Route::put('/admins/{admin}', [AdminController::class, 'update']);
    Route::post('/admins', [AdminController::class, 'store']);
    Route::delete('/admins/{admin}', [AdminController::class, 'destroy']);
});
