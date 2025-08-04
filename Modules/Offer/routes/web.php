<?php

use Illuminate\Support\Facades\Route;
use Modules\Offer\Http\Controllers\OfferController;

Route::middleware(['auth', 'verified', 'approved'])->group(function () {
    Route::resource('offers', OfferController::class)->names('offers');
    Route::get('offer/create/{medicine}', [OfferController::class, 'createOffer'])->name('offers.create');
});
