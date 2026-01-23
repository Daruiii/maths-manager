<?php

use App\Http\Controllers\Chapter\ChapterController;
use App\Http\Controllers\Chapter\SubchapterController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// ============================================
// CHAPTER & SUBCHAPTER ROUTES
// ============================================

// Chapter routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/chapter', [ChapterController::class, 'index'])->name('chapter.index');
        Route::get('/chapter/{id}/create', [ChapterController::class, 'create'])->name('chapter.create');
        Route::post('/chapter', [ChapterController::class, 'store'])->name('chapter.store');
        Route::get('/chapter/{id}/edit', [ChapterController::class, 'edit'])->name('chapter.edit');
        Route::patch('/chapter/{id}', [ChapterController::class, 'update'])->name('chapter.update');
        Route::delete('/chapter/{id}', [ChapterController::class, 'destroy'])->name('chapter.destroy');
    });
});
Route::get('/chapter/{id}', [ChapterController::class, 'show'])->name('chapter.show');

// Subchapter routes
Route::get('/subchapter', [SubchapterController::class, 'index'])->name('subchapter.index');
Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/subchapter/{id}/create', [SubchapterController::class, 'create'])->name('subchapter.create');
    Route::post('/subchapter', [SubchapterController::class, 'store'])->name('subchapter.store');
    Route::get('/subchapter/{id}/edit', [SubchapterController::class, 'edit'])->name('subchapter.edit');
    Route::patch('/subchapter/{id}', [SubchapterController::class, 'update'])->name('subchapter.update');
    Route::delete('/subchapter/{id}', [SubchapterController::class, 'destroy'])->name('subchapter.destroy');
});
Route::get('/subchapter/{id}', [SubchapterController::class, 'show'])->name('subchapter.show');
