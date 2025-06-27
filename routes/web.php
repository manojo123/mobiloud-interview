<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('form.index');
});

Route::prefix('form')->group(function () {
    Route::get('/', [FormController::class, 'index'])->name('form.index');
    Route::get('/step1', [FormController::class, 'step1'])->name('form.step1');
    Route::post('/step1', [FormController::class, 'storeStep1'])->name('form.step1.store');
    Route::get('/step2', [FormController::class, 'step2'])->name('form.step2');
    Route::post('/step2', [FormController::class, 'storeStep2'])->name('form.step2.store');
    Route::get('/step3', [FormController::class, 'step3'])->name('form.step3');
    Route::post('/step3', [FormController::class, 'storeStep3'])->name('form.step3.store');
    Route::get('/step4', [FormController::class, 'step4'])->name('form.step4');
    Route::post('/submit', [FormController::class, 'submit'])->name('form.submit');
});

require __DIR__.'/settings.php';
