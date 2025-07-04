<?php

use Illuminate\Support\Facades\Route;


use App\Livewire\WeddingOrderForm;
use App\Http\Controllers\TransactionFormController;

Route::get('/', WeddingOrderForm::class)->name('wedding.order');
