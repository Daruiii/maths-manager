<?php

use App\Http\Controllers\Teacher\BureauController;
use App\Http\Controllers\Teacher\DmBuilderController;
use App\Http\Controllers\Teacher\DSBuilderController;
use App\Http\Controllers\Teacher\TdBuilderController;
use App\Http\Controllers\Teacher\PrivateExerciseController;
use App\Http\Controllers\Teacher\TeacherGroupController;
use App\Http\Controllers\Teacher\TeacherInvitationController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Http\Controllers\Teacher\TeacherTagController;
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

        // Mon Bureau (dashboard)
        Route::get('/bureau', [BureauController::class, 'index'])->name('bureau.index');
        Route::get('/bureau/history', [BureauController::class, 'history'])->name('bureau.history');

        // Exercices privés
        Route::get('/exercices', [PrivateExerciseController::class, 'index'])->name('exercices.index');
        Route::get('/exercices/create', [PrivateExerciseController::class, 'create'])->name('exercices.create');
        Route::post('/exercices', [PrivateExerciseController::class, 'store'])->name('exercices.store');
        Route::get('/exercices/{exercise}/edit', [PrivateExerciseController::class, 'edit'])->name('exercices.edit');
        Route::put('/exercices/{exercise}', [PrivateExerciseController::class, 'update'])->name('exercices.update');
        Route::delete('/exercices/{exercise}', [PrivateExerciseController::class, 'destroy'])->name('exercices.destroy');
        Route::post('/exercices/{exercise}/images', [PrivateExerciseController::class, 'uploadImage'])->name('exercices.images.upload');
        Route::delete('/exercices/{exercise}/images/{imageName}', [PrivateExerciseController::class, 'deleteImage'])->name('exercices.images.delete');
        // Tags
        Route::post('/tags', [TeacherTagController::class, 'store'])->name('tags.store');
        Route::patch('/tags/{tag}', [TeacherTagController::class, 'update'])->name('tags.update');
        Route::delete('/tags/{tag}', [TeacherTagController::class, 'destroy'])->name('tags.destroy');

        // DS Builder
        Route::get('/ds/create', [DSBuilderController::class, 'create'])->name('ds.create');
        Route::post('/ds/assign', [DSBuilderController::class, 'assign'])->name('ds.assign');

        // DS Builder — API search (JSON)
        Route::get('/ds/builder/problems', [DSBuilderController::class, 'searchProblems'])->name('ds.builder.problems');
        Route::get('/ds/builder/exercises', [DSBuilderController::class, 'searchExercises'])->name('ds.builder.exercises');
        Route::get('/ds/builder/private', [DSBuilderController::class, 'searchPrivate'])->name('ds.builder.private');

        // DM Builder
        Route::get('/dm/create', [DmBuilderController::class, 'create'])->name('dm.create');
        Route::post('/dm/assign', [DmBuilderController::class, 'assign'])->name('dm.assign');

        // DM Builder — API search (JSON)
        Route::get('/dm/builder/problems', [DmBuilderController::class, 'searchProblems'])->name('dm.builder.problems');
        Route::get('/dm/builder/exercises', [DmBuilderController::class, 'searchExercises'])->name('dm.builder.exercises');
        Route::get('/dm/builder/private', [DmBuilderController::class, 'searchPrivate'])->name('dm.builder.private');

        // TD Builder
        Route::get('/td/create', [TdBuilderController::class, 'create'])->name('td.create');
        Route::post('/td/assign', [TdBuilderController::class, 'assign'])->name('td.assign');

        // TD Builder — API search (JSON)
        Route::get('/td/builder/exercises', [TdBuilderController::class, 'searchExercises'])->name('td.builder.exercises');
        Route::get('/td/builder/private', [TdBuilderController::class, 'searchPrivate'])->name('td.builder.private');
    });

// ─── Join via invitation (public preview / auth for accept) ───────────────────
Route::get('/join/{code}', [TeacherInvitationController::class, 'join'])->name('invitation.join');
Route::post('/join/{code}', [TeacherInvitationController::class, 'accept'])->middleware('auth')->name('invitation.accept');
