<?php

use App\Http\Controllers\Sheet\TdController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// ============================================
// TD ROUTES (ex exercises_sheet)
// ============================================

Route::middleware('auth')->group(function () {
    // Admin routes
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/td/create', [TdController::class, 'create'])->name('td.create');
        Route::get('/td/select-chapter', [TdController::class, 'selectChapter'])->name('td.selectChapter');
        Route::post('/td', [TdController::class, 'store'])->name('td.store');
        Route::get('/td', [TdController::class, 'index'])->name('td.index');
        Route::get('/td/{id}/edit', [TdController::class, 'edit'])->name('td.edit');
        Route::patch('/td/{id}', [TdController::class, 'update'])->name('td.update');
        Route::delete('/td/{id}', [TdController::class, 'destroy'])->name('td.destroy');
    });

    // Student routes
    Route::get('/td/{id}', [TdController::class, 'show'])->name('td.show');
    Route::get('/td/my/{id}', [TdController::class, 'indexUser'])->name('td.myTd');
});
