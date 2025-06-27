<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('form.index');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Form routes
Route::prefix('form')->name('form.')->group(function () {
    Route::get('/', [FormController::class, 'index'])->name('index');

    Route::get('/step1', [FormController::class, 'step1'])->name('step1');
    Route::post('/step1', [FormController::class, 'storeStep1'])->name('store-step1');

    Route::get('/step2', [FormController::class, 'step2'])->name('step2');
    Route::post('/step2', [FormController::class, 'storeStep2'])->name('store-step2');

    Route::get('/step3', [FormController::class, 'step3'])->name('step3');
    Route::post('/step3', [FormController::class, 'storeStep3'])->name('store-step3');

    Route::get('/step4', [FormController::class, 'step4'])->name('step4');
    Route::post('/submit', [FormController::class, 'submit'])->name('submit');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
