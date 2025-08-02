<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('users', UserController::class)->names('user');
});

Route::get('/dashboard', function () {
    return view('user::admin\dashboard');
})->middleware(['auth', 'verified', 'approved'])->name('dashboard');

Route::middleware(['auth', 'approved'])->group(function () {
    Route::resource('users', Modules\User\Http\Controllers\UserController::class)->names('users');

    // create user "pharmacits and suppliers"
    Route::get('register-pharmacists', [Modules\User\Http\Controllers\UserController::class, 'create_pharmacists'])
        ->name('register.pharmacists');
    Route::get('register-suppliers', [Modules\User\Http\Controllers\UserController::class, 'create_suppliers'])
        ->name('register.suppliers');

    // store user "pharmacits and suppliers"
    Route::post('register', [Modules\User\Http\Controllers\UserController::class, 'store'])->name('register');

    // get all pharmacists and suppliers
    Route::get('/pharmacists', [Modules\User\Http\Controllers\UserController::class, 'pharmacistsList'])
        ->name('pharmacists.index');
    Route::get('/suppliers', [Modules\User\Http\Controllers\UserController::class, 'suppliersList'])
        ->name('suppliers.index');
    // update profile
    Route::get('/profile/edit', [Modules\User\Http\Controllers\UserController::class, 'edit_profile'])->name('profile.edit');
    Route::patch('/profile/update', [Modules\User\Http\Controllers\UserController::class, 'update_profile'])->name('profile.update');

    Route::delete('/profile', [Modules\User\Http\Controllers\UserController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('guest')->group(function () {

    Route::get('login', [Modules\User\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [Modules\User\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [Modules\User\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [Modules\User\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [Modules\User\Http\Controllers\Auth\NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [Modules\User\Http\Controllers\Auth\NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', Modules\User\Http\Controllers\Auth\EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', Modules\User\Http\Controllers\Auth\VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [Modules\User\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [Modules\User\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [Modules\User\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store']);

    Route::put('password', [Modules\User\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [Modules\User\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
