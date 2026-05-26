<?php

use App\Http\Controllers\Student\StudentAssignmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('student')->name('student.')->group(function () {
    Route::get('/ressources', [StudentAssignmentController::class, 'ressources'])->name('ressources');
    Route::get('/ressources/devoirs', [StudentAssignmentController::class, 'index'])->name('assignments.index');
    Route::redirect('/devoirs', '/student/ressources/devoirs');
});
