<?php

use Illuminate\Support\Facades\Route;
use Modules\Medicine\Http\Controllers\MedicineController;

Route::middleware(['auth', 'approved'])->group(function () {
    Route::resource('medicines', MedicineController::class)->names('medicines');
    Route::post('add-medicine-checked', [MedicineController::class, 'storeCheckedMedicine'])->name('checked-medicine');
    Route::get('my-medicines', [MedicineController::class, 'getMedicinesBySupplier'])->name('my-medicines');
    Route::post('/medicines/import', [MedicineController::class, 'import'])->name('medicines.import');
    Route::post('/medicines/{medicine}/toggle-availability', [MedicineController::class, 'toggleAvailability'])
        ->name('medicines.toggle-availability');
    Route::get('/medicines/image/{media}', [MedicineController::class, 'showImage'])->name('medicines.image');
});
