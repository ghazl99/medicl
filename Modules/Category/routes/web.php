<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::middleware(['auth', 'verified','approved'])->group(function () {
    Route::resource('categories', CategoryController::class)->names('category');
    Route::get('/category/image/{media}', [CategoryController::class, 'showImage'])->name('category.image');

});
