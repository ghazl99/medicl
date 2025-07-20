<?php

use Illuminate\Support\Facades\Route;
use Modules\Medicine\Http\Controllers\MedicineController;

Route::middleware(['auth'])->group(function () {
    Route::resource('medicines', MedicineController::class)->names('medicines');
    Route::post('add-medicine-checked', [MedicineController::class, 'storeCheckedMedicine'])->name('checked-medicine');
    Route::get('my-medicines', [MedicineController::class, 'getMedicinesBySupplier'])->name('my-medicines');
});
