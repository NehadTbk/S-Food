<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Cart + Checkout (requires login, user role enforced in controller)
Route::middleware('auth')->group(function () {
    Route::post('/winkelmandje/toevoegen', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/winkelmandje/bijwerken', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/winkelmandje/verwijderen', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/winkelmandje/leegmaken', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});
Route::get('/winkelmandje/samenvatting', [CartController::class, 'summary'])->name('cart.summary');

// Public profile
Route::get('/profiel/{username}', [UserProfileController::class, 'show'])->name('profile.show');
Route::patch('/profiel/{username}', [UserProfileController::class, 'update'])->middleware('auth')->name('profile.update.public');


require __DIR__.'/auth.php';
