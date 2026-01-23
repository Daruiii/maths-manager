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
        // verify et unverify
        Route::patch('/user/{id}/verify', [UserController::class, 'verify'])->name('user.verify');
        Route::patch('/user/{id}/unverify', [UserController::class, 'unverify'])->name('user.unverify');
        // reset last_ds_generated_at
        Route::patch('/user/{id}/resetLastDSGeneratedAt', [UserController::class, 'resetLastDSGeneratedAt'])->name('user.resetLastDSGeneratedAt');
    });
});

// Profile routes (authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
