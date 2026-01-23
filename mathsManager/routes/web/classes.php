<?php

use App\Http\Controllers\Classe\ClasseController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// ============================================
// CLASSE ROUTES
// ============================================

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/classe/reorder', [ClasseController::class, 'reorderAllElements'])->name('classe.reorder');
        Route::get('/classe', [ClasseController::class, 'index'])->name('classe.index');
        Route::get('/classe/create', [ClasseController::class, 'create'])->name('classe.create');
        Route::post('/classe', [ClasseController::class, 'store'])->name('classe.store');
        Route::get('/classe/{id}/edit', [ClasseController::class, 'edit'])->name('classe.edit');
        Route::patch('/classe/{id}', [ClasseController::class, 'update'])->name('classe.update');
        Route::delete('/classe/{id}', [ClasseController::class, 'destroy'])->name('classe.destroy');
    });
});
Route::get('/classe/{level}', [ClasseController::class, 'show'])->name('classe.show');
