<?php

use App\Http\Controllers\Teacher\TeacherGroupController;
use App\Http\Controllers\Teacher\TeacherInvitationController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Http\Middleware\IsTeacher;
use Illuminate\Support\Facades\Route;

// ─── Teacher routes (auth + validated teacher only) ───────────────────────────
Route::middleware(['auth', IsTeacher::class])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        // Students
        Route::get('/students', [TeacherStudentController::class, 'index'])->name('students.index');
        Route::get('/students/groups/{group}', [TeacherStudentController::class, 'showGroup'])->name('students.group');
        Route::delete('/students/{student}', [TeacherStudentController::class, 'removeStudent'])->name('students.remove');
        Route::patch('/students/{student}/group', [TeacherStudentController::class, 'updateGroup'])->name('students.updateGroup');

        // Groups CRUD
        Route::post('/groups', [TeacherGroupController::class, 'store'])->name('groups.store');
        Route::patch('/groups/{group}', [TeacherGroupController::class, 'update'])->name('groups.update');
        Route::delete('/groups/{group}', [TeacherGroupController::class, 'destroy'])->name('groups.destroy');

        // Invitation
        Route::post('/invitation', [TeacherInvitationController::class, 'configure'])->name('invitation.configure');
    });

// ─── Join via invitation (public preview / auth for accept) ───────────────────
Route::get('/join/{code}', [TeacherInvitationController::class, 'join'])->name('invitation.join');
Route::post('/join/{code}', [TeacherInvitationController::class, 'accept'])->middleware('auth')->name('invitation.accept');
