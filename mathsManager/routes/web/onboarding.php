<?php

use App\Http\Controllers\Onboarding\OnboardingController;
use Illuminate\Support\Facades\Route;

// ============================================
// ONBOARDING ROUTES
// ============================================
// Ces routes sont exclues de CheckOnboarding pour éviter les redirect loops.

Route::middleware('auth')->prefix('onboarding')->name('onboarding.')->group(function () {
    // Sélection du rôle (première connexion)
    Route::get('/role', [OnboardingController::class, 'role'])->name('role');
    Route::post('/student', [OnboardingController::class, 'chooseStudent'])->name('student');
    Route::get('/teacher', [OnboardingController::class, 'chooseTeacher'])->name('teacher');
    Route::post('/teacher', [OnboardingController::class, 'submitTeacherForm'])->name('teacher.submit');

    // Pages de statut
    Route::get('/pending', [OnboardingController::class, 'pending'])->name('pending');
    Route::get('/rejected', [OnboardingController::class, 'rejected'])->name('rejected');
    Route::post('/switch-to-student', [OnboardingController::class, 'switchToStudent'])->name('switch-to-student');
});
