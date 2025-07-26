<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\WizardController;

Route::prefix('wizard')->group(function () {
    Route::get('/venue-types', [WizardController::class, 'venueTypes']);
    Route::get('/venues', [WizardController::class, 'venues']); // ?type=...
    Route::get('/vendor-categories', [WizardController::class, 'vendorCategories']); // ?venue_id=...
    Route::get('/vendors', [WizardController::class, 'vendors']); // ?category_id=...&venue_id=...
    Route::get('/caterings', [WizardController::class, 'caterings']); // ?venue_id=...
    Route::get('/discounts', [WizardController::class, 'discounts']); // ?venue_id=...&catering_id=...&vendor_ids[]=...
    Route::post('/transaction', [WizardController::class, 'storeTransaction']);
});

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
