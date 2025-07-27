<?php

use App\Livewire\WeddingOrderForm;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\TransactionFormController;

// Route::get('/', function () {
//     return view('wizard.index');
// });

Route::get('/wizard/recap/{transaction}', [WizardController::class, 'downloadRecapPdf'])->name('wizard.recap.pdf');

// Recap page (make sure this is *after* any other conflicting routes!)
Route::get('recap/{recap_link}', [WizardController::class, 'showRecap'])->name('wizard.recap');