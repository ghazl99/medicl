<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('orders', OrderController::class)->names('orders');
    Route::get('api/supplier/{id}/medicines', [OrderController::class, 'getMedicinesBySupplier']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/reject-medicine/{medicine}', [OrderController::class, 'rejectMedicine'])
        ->name('orders.reject-medicine');
});
