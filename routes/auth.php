<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('registreren', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('registreren', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('wachtwoord/vergeten', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('wachtwoord/vergeten', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('wachtwoord/reset/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('wachtwoord/reset', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
