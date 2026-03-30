<?php

use App\Http\Controllers\Teacher\DSBuilderController;
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

        // DS Builder
        Route::get('/ds/create', [DSBuilderController::class, 'create'])->name('ds.create');
        Route::post('/ds/assign', [DSBuilderController::class, 'assign'])->name('ds.assign');

        // DS Builder — API search (JSON)
        Route::get('/ds/builder/problems', [DSBuilderController::class, 'searchProblems'])->name('ds.builder.problems');
        Route::get('/ds/builder/exercises', [DSBuilderController::class, 'searchExercises'])->name('ds.builder.exercises');
        Route::get('/ds/builder/private', [DSBuilderController::class, 'searchPrivate'])->name('ds.builder.private');
    });

// ─── Join via invitation (public preview / auth for accept) ───────────────────
Route::get('/join/{code}', [TeacherInvitationController::class, 'join'])->name('invitation.join');
Route::post('/join/{code}', [TeacherInvitationController::class, 'accept'])->middleware('auth')->name('invitation.accept');
