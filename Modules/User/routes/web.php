<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Auth\AuthenticatedSessionController;
use Modules\User\Http\Controllers\Auth\ConfirmablePasswordController;
use Modules\User\Http\Controllers\Auth\EmailVerificationNotificationController;
use Modules\User\Http\Controllers\Auth\EmailVerificationPromptController;
use Modules\User\Http\Controllers\Auth\NewPasswordController;
use Modules\User\Http\Controllers\Auth\PasswordController;
use Modules\User\Http\Controllers\Auth\PasswordResetLinkController;
use Modules\User\Http\Controllers\Auth\VerifyEmailController;
use Modules\User\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
| Routes accessible to guests (unauthenticated users)
*/

Route::middleware('guest')->group(function () {
    // Supplier registration form
    Route::get('register-suppliers', [UserController::class, 'create_suppliers'])->name('register.suppliers');

    // Store new pharmacist or supplier
    Route::post('register', [UserController::class, 'store'])->name('register');

    // Login form
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    // Handle login
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Forgot password
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Reset password
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
| Routes accessible only to authenticated users
*/

Route::middleware('auth')->group(function () {
    // Email verification notice
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    // Verify email link
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Resend email verification
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Confirm password
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Update password
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Verified and Approved User Routes
|--------------------------------------------------------------------------
| Routes for users who are both verified and approved
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard page
    Route::get('/dashboard', function () {
        return view('user::admin.dashboard');
    })->middleware(['approved'])->name('dashboard');

    // User resource routes (protected)
    Route::resource('users', UserController::class)->names('user');
});

Route::middleware(['auth', 'approved'])->group(function () {
    // User management
    Route::resource('users', UserController::class)->names('users');

    // Create pharmacist form
    Route::get('register-pharmacists', [UserController::class, 'create_pharmacists'])->name('register.pharmacists');

    // List all pharmacists
    Route::get('/pharmacists', [UserController::class, 'pharmacistsList'])->name('pharmacists.index');

    // List all suppliers
    Route::get('/suppliers', [UserController::class, 'suppliersList'])->name('suppliers.index');

    // Edit profile
    Route::get('/profile/edit', [UserController::class, 'edit_profile'])->name('profile.edit');

    // Update profile
    Route::patch('/profile/update', [UserController::class, 'update_profile'])->name('profile.update');

    // Delete profile
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');
});
