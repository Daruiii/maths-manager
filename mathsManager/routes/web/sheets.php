<?php

use App\Http\Controllers\Sheet\ExercisesSheetController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsVerified;
use Illuminate\Support\Facades\Route;

// ============================================
// EXERCISES SHEET ROUTES
// ============================================

Route::middleware('auth')->group(function () {
    // Admin routes
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/exercises_sheet/create', [ExercisesSheetController::class, 'create'])->name('exercises_sheet.create');
        Route::get('/exercises_sheet/select-chapter', [ExercisesSheetController::class, 'selectChapter'])->name('exercises_sheet.selectChapter');
        Route::post('/exercises_sheet', [ExercisesSheetController::class, 'store'])->name('exercises_sheet.store');
        Route::get('/exercises_sheets', [ExercisesSheetController::class, 'index'])->name('exercises_sheet.index');
        Route::get('/exercises_sheet/{id}/edit', [ExercisesSheetController::class, 'edit'])->name('exercises_sheet.edit');
        Route::patch('/exercises_sheet/{id}', [ExercisesSheetController::class, 'update'])->name('exercises_sheet.update');
        Route::delete('/exercises_sheet/{id}', [ExercisesSheetController::class, 'destroy'])->name('exercises_sheet.destroy');
    });
    
    // Student routes
    Route::middleware([IsVerified::class])->group(function () {
        Route::get('/exercises_sheet/{id}', [ExercisesSheetController::class, 'show'])->name('exercises_sheet.show');
        Route::get('/exercises_sheet/myExercisesSheets/{id}', [ExercisesSheetController::class, 'indexUser'])->name('exercises_sheet.myExercisesSheets');
    });
});
