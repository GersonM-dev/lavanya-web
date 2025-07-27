<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;

Route::get('/hello', function() {
    return response()->json(['hello' => 'world']);
});

Route::prefix('form')->group(function () {
    Route::get('venue-types', [FormController::class, 'getVenueTypes'])->name('form.venue-types');
    Route::get('venues', [FormController::class, 'getVenue'])->name('form.venues');
    Route::get('caterings', [FormController::class, 'getCaterings'])->name('form.caterings');
    Route::get('vendor-categories', [FormController::class, 'getVendorCategories'])->name('form.vendor-categories');
    Route::get('vendors', [FormController::class, 'getVendors'])->name('form.vendors');
    Route::get('discounts', [FormController::class, 'getDiscounts'])->name('form.discounts');
    Route::post('wedding', [FormController::class, 'postWedding'])->name('form.wedding');
});
