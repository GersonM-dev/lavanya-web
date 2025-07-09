<?php

use App\Livewire\WeddingOrderForm;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\TransactionFormController;

// Route::get('/', function () {
//     return view('wizard.index');
// });

Route::get('/wizard/recap/{transaction}', [\App\Http\Controllers\WizardController::class, 'downloadRecapPdf'])->name('wizard.recap.pdf');

Route::get('/{referral:slug?}', [WizardController::class, 'index']);
