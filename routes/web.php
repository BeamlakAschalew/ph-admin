<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ConsumerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Consumer\ConsumerAuthController;
use App\Http\Controllers\Consumer\HomeController;
use App\Http\Controllers\Consumer\OrderController as ConsumerOrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Supplier\SupplierAuthController;
use App\Http\Controllers\Supplier\SupplierHomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['admin.guest']], function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login']);

        Route::get('/signup', [AuthController::class, 'showRegister'])->name('signup');
        Route::post('/signup', [AuthController::class, 'register']);
    });

    Route::group(['middleware' => ['admin.authenticated']], function () {
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

        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    });

    Route::group(['middleware' => ['admin.authenticated', 'role:superadmin']], function () {
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

    Route::group(['middleware' => ['consumer.guest']], function () {
        Route::get('/login', [ConsumerAuthController::class, 'showLogin'])->name('consumer.login');
        Route::post('/login', [ConsumerAuthController::class, 'login']);

        Route::get('/signup', [ConsumerAuthController::class, 'showSignup'])->name('consumer.signup');
        Route::post('/signup', [ConsumerAuthController::class, 'register']);
    });

    Route::group(['middleware' => ['no-auth-choose', 'consumer.authenticated', 'consumer.approved']], function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/past-orders', [ConsumerOrderController::class, 'pastOrders'])->name('consumer.past-orders');
        Route::post('/checkout', [ConsumerOrderController::class, 'checkout']);
        Route::put('/profile', [ConsumerAuthController::class, 'updateProfile'])->name('consumer.profile.update');
    });

    Route::post('/logout', [ConsumerAuthController::class, 'logout'])->name('consumer.logout')->middleware('consumer.authenticated');

    Route::group(['middleware' => ['consumer.authenticated', 'consumer.not-approved']], function () {
        Route::get('/not-approved', function () {
            return Inertia::render('Consumer/NotApproved');
        });
    });
});

Route::prefix('/supplier')->group(function () {

    Route::group(['middleware' => ['supplier.guest']], function () {
        Route::get('/login', [SupplierAuthController::class, 'showLogin'])->name('supplier.login');
        Route::post('/login', [SupplierAuthController::class, 'login']);

        Route::get('/signup', [SupplierAuthController::class, 'showSignup'])->name('supplier.signup');
        Route::post('/signup', [SupplierAuthController::class, 'register']);
    });

    Route::group(['middleware' => ['no-auth-choose', 'supplier.authenticated', 'supplier.approved']], function () {
        Route::get('/', [SupplierHomeController::class, 'index'])->name('supplier.home');
        Route::put('/profile', [SupplierAuthController::class, 'updateProfile'])->name('supplier.profile.update');
    });

    Route::post('/logout', [SupplierAuthController::class, 'logout'])->name('supplier.logout')->middleware('supplier.authenticated');

    Route::group(['middleware' => ['supplier.authenticated', 'supplier.not-approved']], function () {
        Route::get('/not-approved', function () {
            return Inertia::render('Supplier/NotApproved');
        });
    });
});
Route::get('/choose-role', function () {
    if (auth('consumer')->check() && auth('supplier')->check()) {
        return redirect()->route('home');
    } elseif (auth('consumer')->check()) {
        return redirect()->route('home');
    } elseif (auth('supplier')->check()) {
        return redirect()->route('supplier.home');
    }

    return Inertia::render('ChooseRole');
})->name('choose-role');

Route::post('/contact', ContactController::class)->name('contact');
Route::get('/about-us', function () {
    return Inertia::render('About');
})->name('about-us');
