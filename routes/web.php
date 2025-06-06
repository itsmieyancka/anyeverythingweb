<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductRatingController;
use App\Models\Category;

// ✅ Public Routes
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/categories/{slug}', [ProductController::class, 'showCategory'])->name('categories.show');
Route::get('/departments', [ProductController::class, 'showDepartments'])->name('departments.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/vendor/products', [ProductController::class, 'index'])->name('vendor.products.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add'); // removed duplicate
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/stripe/payment-intent', [PaymentController::class, 'createPaymentIntent'])->name('stripe.paymentIntent');
Route::post('/ratings', [ProductRatingController::class, 'store'])->name('ratings.store')->middleware('auth');
Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
Route::get('/departments', [ProductController::class, 'showDepartments'])->name('departments.index');
Route::get('/categories/{slug}', [ProductController::class, 'showCategory'])->name('categories.show');
Route::get('/categories', [ProductController::class, 'indexCategories'])->name('categories.index');
Route::get('/order/confirmed/{order}', [CheckoutController::class, 'confirmed'])->name('order.confirmed');



// ✅ Public (read-only) access to all products (optional)
Route::resource('products', ProductController::class)->only(['index']);

// ✅ Routes for authenticated and verified users
Route::middleware(['auth', 'verified'])->group(function () {

    // User dashboard - only for users with 'user' role
    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    });

    // Vendor dashboard - only for users with 'vendor' role
    Route::middleware('role:vendor')->prefix('vendor')->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('vendor.dashboard');

        // ✅ Vendor-only product CRUD (keeps /vendor/products/ namespace)
        Route::resource('products', ProductController::class)->except(['index', 'show']);
    });

    // ✅ Profile management (shared by both users and vendors)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Authentication (login, register, etc.)
require __DIR__.'/auth.php';
