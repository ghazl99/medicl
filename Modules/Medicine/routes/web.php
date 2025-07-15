<?php

use Illuminate\Support\Facades\Route;
use Modules\Medicine\Http\Controllers\MedicineController;

Route::middleware(['auth'])->group(function () {
    Route::resource('medicines', MedicineController::class)->names('medicines');
});
