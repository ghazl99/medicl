<?php

use Illuminate\Support\Facades\Route;
use Modules\Pharmacist\Http\Controllers\PharmacistController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::resource('pharmacists', PharmacistController::class)->names('pharmacist');
    Route::get('pharmacists/home', [PharmacistController::class, 'home'])->name('pharmacist.home');
    Route::get('/main-categories', [PharmacistController::class, 'getMainCategories'])
        ->name('main.categories');
    Route::get('/sub-categories/{id}', [PharmacistController::class, 'getSubCategories'])
        ->name('sub.categories');
    Route::get('/sub-categories-medicines/{id}', [PharmacistController::class, 'medicinesBySubCategory'])
        ->name('sub.categories.medicines');
    Route::get('/new-medicines', [PharmacistController::class, 'NewMedicines'])
        ->name('new.medicines');
    Route::get('/my-orders', [PharmacistController::class, 'myOrders'])
        ->name('my.orders');
    Route::get('/details-order/{id}', [PharmacistController::class, 'detailsOrders'])
        ->name('details.order');
    Route::get('/notifications', [PharmacistController::class, 'notifications'])
        ->name('notifications');
});
