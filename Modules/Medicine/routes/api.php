<?php

use Illuminate\Support\Facades\Route;
use Modules\Medicine\Http\Controllers\MedicineController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('medicines', MedicineController::class)->names('medicine');
});
