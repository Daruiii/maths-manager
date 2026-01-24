<?php

use App\Http\Controllers\Exercise\ExerciseController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// ============================================
// EXERCISE ROUTES
// ============================================

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        // Exercise CRUD
        Route::get('/exercise/{id}/create', [ExerciseController::class, 'create'])->name('exercise.create');
        Route::post('/exercise', [ExerciseController::class, 'store'])
            ->middleware('throttle:30,1') // 30 exercise creations per minute max (file uploads)
            ->name('exercise.store');
        Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
        Route::get('/exercises/decrement', [ExerciseController::class, 'decrementAllExercises'])->name('exercises.decrement');
        Route::post('/exercises/update-order', [ExerciseController::class, 'updateOrder'])->name('exercises.updateOrder');
        Route::get('/exercise/{id}', [ExerciseController::class, 'show'])->name('exercise.show');
        Route::get('/exercise/{id}/edit', [ExerciseController::class, 'edit'])->name('exercise.edit');
        Route::patch('/exercise/{id}', [ExerciseController::class, 'update'])->name('exercise.update');
        Route::delete('/exercise/{id}', [ExerciseController::class, 'destroy'])->name('exercise.destroy');
    });
});
