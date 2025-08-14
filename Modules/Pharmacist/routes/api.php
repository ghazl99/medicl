<?php

use Illuminate\Support\Facades\Route;
use Modules\Pharmacist\Http\Controllers\PharmacistController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('pharmacists', PharmacistController::class)->names('pharmacist');
});
