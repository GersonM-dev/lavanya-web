<?php

use App\Livewire\WeddingOrderForm;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\TransactionFormController;

// Route::get('/', function () {
//     return view('wizard.index');
// });

Route::get('/wizard/recap/{transaction}', [WizardController::class, 'downloadRecapPdf'])->name('wizard.recap.pdf');

Route::prefix('api/wizard')->group(function () {
    Route::get('venue-types', [WizardController::class, 'venueTypes']);
    Route::get('venues', [WizardController::class, 'venues']);
    Route::get('vendor-categories', [WizardController::class, 'vendorCategories']);
    Route::get('vendors', [WizardController::class, 'vendors']);
    Route::get('caterings', [WizardController::class, 'caterings']);
    Route::post('discounts', [WizardController::class, 'discounts']); // Use POST if sending a list of IDs
    Route::post('store', [WizardController::class, 'storeTransaction']);
});


Route::get('/{referral:slug?}', [WizardController::class, 'index']);

// Recap page (make sure this is *after* any other conflicting routes!)
Route::get('recap/{recap_link}', [WizardController::class, 'showRecap'])->name('wizard.recap');