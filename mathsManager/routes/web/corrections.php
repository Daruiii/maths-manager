<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CorrectionRequestController;
use App\Http\Middleware\IsAdmin;

/*
|--------------------------------------------------------------------------
| Correction Request Routes
|--------------------------------------------------------------------------
|
| Routes pour les demandes de correction de DS
| Divisees en admin (correction, gestion) et student (envoi, consultation)
|
*/

Route::middleware('auth')->group(function () {

    // ============================================
    // ADMIN - Correction Management
    // ============================================
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/correctionRequest', [CorrectionRequestController::class, 'index'])->name('correctionRequest.index');
        Route::get('/correctionRequest/correct/{ds_id}', [CorrectionRequestController::class, 'showCorrectionForm'])->name('correctionRequest.correctForm');
        Route::post('/correctionRequest/correct/{ds_id}', [CorrectionRequestController::class, 'correctCorrectionRequest'])->name('correctionRequest.correct');
        Route::delete('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'destroyCorrectionRequest'])->name('correctionRequest.destroy');
    });

    // ============================================
    // STUDENT - Correction Requests
    // ============================================
    Route::get('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'showCorrectionRequestForm'])->name('correctionRequest.showCorrectionRequestForm');
    Route::post('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'sendCorrectionRequest'])
        ->middleware('throttle:10,1')
        ->name('correctionRequest.sendCorrectionRequest');
    Route::get('/correctionRequest/show/{ds_id}', [CorrectionRequestController::class, 'showCorrectionRequest'])->name('correctionRequest.show');
    Route::get('/correctionRequest/edit/{ds_id}', [CorrectionRequestController::class, 'edit'])->name('correctionRequest.edit');
    Route::put('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'update'])->name('correctionRequest.update');
});
