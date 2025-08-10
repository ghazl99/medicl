<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::middleware(['auth', 'verified', 'approved'])->group(function () {
    // Resource routes for orders (index, create, store, show, edit, update, destroy)
    Route::resource('orders', OrderController::class)->names('orders');

    // Get medicines for a supplier via API
    Route::get('api/supplier/{id}/medicines', [OrderController::class, 'getMedicinesBySupplier'])
        ->name('orders.api.supplier.medicines');

    // Update order status
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');

    // Reject a specific medicine in an order with a note
    Route::patch('/orders/{order}/reject-medicine/{medicine}', [OrderController::class, 'rejectMedicine'])
        ->name('orders.reject-medicine');

    // Update the quantity of a medicine in an order
    Route::patch('/orders/{order}/medicines/{medicine}/update-quantity', [OrderController::class, 'updateMedicineQuantity'])
        ->name('orders.update-medicine-quantity');
});
