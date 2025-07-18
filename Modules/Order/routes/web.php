<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('orders', OrderController::class)->names('orders');
    Route::get('api/supplier/{id}/medicines', [OrderController::class, 'getMedicinesBySupplier']);

});
