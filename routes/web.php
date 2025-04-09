<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ConsumerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Consumer\ConsumerAuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);

        Route::get('/signup', [AuthController::class, 'showRegister'])->name('signup');
        Route::post('/signup', [AuthController::class, 'register']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [OrderController::class, 'index'])->name('dashboard');

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/import', [ProductController::class, 'importFromExcel'])->name('products.import');

        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/consumers', [ConsumerController::class, 'index'])->name('consumers.index');

        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    });



    Route::group(['middleware' => ['auth', 'role:superadmin']], function () {
        Route::get('/pending-consumers', [ConsumerController::class, 'pendingIndex'])->name('pending-consumers.index');
        Route::get('/pending-suppliers', [SupplierController::class, 'pendingIndex'])->name('pending-suppliers.index');

        Route::post('/pending-consumers', [ConsumerController::class, 'approveOrReject'])->name('approve-consumer');
        Route::post('/pending-suppliers', [SupplierController::class, 'approveOrReject'])->name('approve-supplier');

        Route::get('/admins', [AdminController::class, 'index']);

        Route::put('/admins/{admin}', [AdminController::class, 'update']);
        Route::post('/admins', [AdminController::class, 'store']);
        Route::delete('/admins/{admin}', [AdminController::class, 'destroy']);

        Route::put('/consumers/{consumer}', [ConsumerController::class, 'update'])->name('consumers.update');
        Route::post('/consumers', [ConsumerController::class, 'store'])->name('consumers.store');
        Route::delete('/consumers/{consumer}', [ConsumerController::class, 'destroy'])->name('consumers.destroy');

        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        Route::put('/superadmin-secret', [AdminController::class, 'updateSuperAdminSecret']);
    });
});

Route::prefix('/')->group(function () {
    Route::middleware('guest:consumer')->group(function () {
        Route::get('/login', [ConsumerAuthController::class, 'showLogin'])->name('consumer.login');
        Route::post('/login', [ConsumerAuthController::class, 'login']);

        Route::get('/signup', [ConsumerAuthController::class, 'showSignup'])->name('consumer.signup');
        Route::post('/signup', [ConsumerAuthController::class, 'register']);
    });

    // Route::middleware('auth:consumer')->group(function () {
    //     Route::post('/logout', [ConsumerAuthController::class, 'logout'])->name('consumer.logout');
    // });
});
Route::get('/', function () {
    return Inertia::render('Consumer/Home');
});
