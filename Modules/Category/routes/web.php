<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

// Routes protected by auth, email verification, and approval middleware
Route::middleware(['auth', 'verified', 'approved'])->group(function () {
    // Resource routes for category CRUD with named routes prefix 'category'
    Route::resource('categories', CategoryController::class)->names('category');

    // Route to show category image by media ID
    Route::get('/category/image/{media}', [CategoryController::class, 'showImage'])->name('category.image');

    // Route to get subcategories for sidebar
    Route::get('/subcategories', [CategoryController::class, 'sidebar'])->name('categories.subcategories.sidebar');
});
