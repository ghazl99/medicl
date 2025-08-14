<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('carts', CartController::class)->names('cart');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/delete/{id}', [CartController::class, 'deleteItem'])->name('cart.delete');
});
