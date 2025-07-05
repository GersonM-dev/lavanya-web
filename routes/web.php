<?php

use Illuminate\Support\Facades\Route;


use App\Livewire\WeddingOrderForm;
use App\Http\Controllers\TransactionFormController;

Route::get('/', function () {
    return view('wizard.index');
});

Route::get('/wizard/recap/{transaction}', [\App\Http\Controllers\WizardController::class, 'downloadRecapPdf'])->name('wizard.recap.pdf');

