<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// ============================================
// USER & PROFILE ROUTES
// ============================================

// User management (admin only)
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/students', [UserController::class, 'showStudents'])->name('students.show');
        Route::get('/user/{student_id}/quizzes', [UserController::class, 'showQuizzes'])->name('student.quizzes');
        Route::get('/user/quizzes/{quiz_id}/details', [UserController::class, 'showQuizDetails'])->name('student.quizDetails');
        Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::patch('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        // activate, deactivate, ban
        Route::patch('/user/{id}/activate', [UserController::class, 'activate'])->name('user.activate');
        Route::patch('/user/{id}/deactivate', [UserController::class, 'deactivate'])->name('user.deactivate');
        Route::patch('/user/{id}/ban', [UserController::class, 'ban'])->name('user.ban');

    });
});

// Profile routes (authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/macros', [ProfileController::class, 'updateMacros'])->name('profile.macros.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
