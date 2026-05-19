<?php

use App\Http\Controllers\Student\StudentAssignmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('student')->name('student.')->group(function () {
    Route::get('/devoirs', [StudentAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/ressources', [StudentAssignmentController::class, 'ressources'])->name('ressources');
});
