<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\ProductController;

// Public route
Route::get('/', function () {
    return view('welcome');
});

// Routes for authenticated and verified users
Route::middleware(['auth', 'verified'])->group(function () {

    // User dashboard - only for users with 'user' role
    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    });

    // Vendor dashboard - only for users with 'vendor' role
    Route::middleware('role:vendor')->prefix('vendor')->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('vendor.dashboard');

        // ✅ Product resource CRUD routes for vendors
        Route::resource('products', ProductController::class);
    });

    // Profile management (shared by both users and vendors)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication routes (login, register, etc.)
require __DIR__.'/auth.php';


