<?php

use Illuminate\Support\Facades\Route;
use Modules\Medicine\Http\Controllers\MedicineController;

/*
|--------------------------------------------------------------------------
| Medicine Module Routes
|--------------------------------------------------------------------------
|
| These routes handle CRUD operations, import, and AJAX requests related
| to medicines. Routes are protected by 'auth' and 'approved' middleware.
|
*/

Route::middleware(['auth', 'approved'])->group(function () {
    // Resource routes for medicine CRUD operations
    Route::resource('medicines', MedicineController::class)->names('medicines');

    // Route to assign selected medicines to supplier
    Route::post('add-medicine-checked', [MedicineController::class, 'storeCheckedMedicine'])->name('checked-medicine');

    // Supplier's own medicines listing
    Route::get('my-medicines', [MedicineController::class, 'getMedicinesBySupplier'])->name('my-medicines');

    // Import medicines via Excel upload
    Route::post('/medicines/import', [MedicineController::class, 'import'])->name('medicines.import');

    // Toggle availability status for a medicine for the logged-in supplier
    Route::post('/medicines/{medicine}/toggle-availability', [MedicineController::class, 'toggleAvailability'])
        ->name('medicines.toggle-availability');

    // Display medicine image via media id
    Route::get('/medicines/image/{media}', [MedicineController::class, 'showImage'])->name('medicines.image');

    // AJAX route to update note on medicine_user pivot
    Route::post('/medicine-user/{id}/update-note', [MedicineController::class, 'updateNote']);

    Route::post('/medicines/{medicine}/toggle-new', [MedicineController::class, 'toggleNewStatus'])
        ->name('medicines.toggle-new');

    Route::get('/medicines-new', [MedicineController::class, 'newMedicines'])->name('medicines.new');

    Route::post('/medicine-user/{id}/update-offer', [MedicineController::class, 'updateOffer']);

});
