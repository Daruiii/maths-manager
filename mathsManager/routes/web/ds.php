<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DS\DSController;
use App\Http\Controllers\DS\DSPlayController;
use App\Http\Controllers\DS\DSManagementController;
use App\Http\Controllers\DS\ProblemController;
use App\Http\Controllers\DS\MultipleChapterController;
use App\Http\Middleware\IsAdmin;

/*
|--------------------------------------------------------------------------
| DS Routes
|--------------------------------------------------------------------------
|
| Routes pour la gestion des DS (devoirs surveillés)
| Divisées en 3 contrôleurs selon les responsabilités :
| - DSController : Listing et affichage (lecture seule)
| - DSPlayController : Flux de jeu (étudiant)
| - DSManagementController : CRUD et assignation (admin/teacher)
|
*/

// Public routes for MultipleChapter color changes (legacy utility routes)
Route::get('/changeAnalyse2Color', [MultipleChapterController::class, 'changeAnalyse2Color'])->name('changeAnalyse2Color');
Route::get('/changeSuitesColor', [MultipleChapterController::class, 'changeSuitesColor'])->name('changeSuitesColor');
Route::get('/changeAnalyse1Color', [MultipleChapterController::class, 'changeAnalyse1Color'])->name('changeAnalyse1Color');

Route::middleware('auth')->group(function () {
    
    // ============================================
    // ADMIN - DS Management
    // ============================================
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        // Listing
        Route::get('/ds', [DSController::class, 'index'])->name('ds.index');
        
        // CRUD (admin only: edit, update, delete)
        Route::get('/ds/{id}/edit', [DSManagementController::class, 'edit'])->name('ds.edit');
        Route::patch('/ds/{id}', [DSManagementController::class, 'update'])->name('ds.update');
        Route::delete('/ds/{id}', [DSManagementController::class, 'destroy'])->name('ds.destroy');
        
        // Assignment (admin only)
        Route::get('/ds/assign', [DSManagementController::class, 'assignForm'])->name('ds.assign');
        Route::post('/ds/assign', [DSManagementController::class, 'assign'])->name('ds.assign.store');
        Route::get('/ds/reAssign/{id}', [DSManagementController::class, 'reAssignForm'])->name('ds.reAssignForm');
        Route::post('/ds/reAssign', [DSManagementController::class, 'reAssign'])->name('ds.reAssign');
        
        // Problems CRUD (admin only)
        Route::get('/problems/create', [ProblemController::class, 'create'])->name('problems.create');
        Route::post('/problems', [ProblemController::class, 'store'])
            ->middleware('throttle:30,1') // 30 problem creations per minute max (file uploads)
            ->name('problems.store');
        Route::get('/problems', [ProblemController::class, 'index'])->name('problems.index');
        Route::get('/problems/{id}/edit/{filter}', [ProblemController::class, 'edit'])->name('problems.edit');
        Route::get('/problems/{id}/filter/{filter}', [ProblemController::class, 'show'])->name('problems.show');
        Route::patch('/problems/{id}', [ProblemController::class, 'update'])->name('problems.update');
        Route::delete('/problems/{id}', [ProblemController::class, 'destroy'])->name('problems.destroy');
        
        // Multiple Chapters CRUD (admin only - used for DS creation)
        Route::get('/multiple_chapters/create', [MultipleChapterController::class, 'create'])->name('multiple_chapter.create');
        Route::post('/multiple_chapters', [MultipleChapterController::class, 'store'])->name('multiple_chapter.store');
        Route::get('/multiple_chapters', [MultipleChapterController::class, 'index'])->name('multiple_chapters.index');
        Route::get('/multiple_chapters/{id}', [MultipleChapterController::class, 'show'])->name('multiple_chapters.show');
        Route::get('/multiple_chapters/{id}/edit', [MultipleChapterController::class, 'edit'])->name('multiple_chapter.edit');
        Route::patch('/multiple_chapters/{id}', [MultipleChapterController::class, 'update'])->name('multiple_chapter.update');
        Route::delete('/multiple_chapters/{id}', [MultipleChapterController::class, 'destroy'])->name('multiple_chapter.destroy');
    });
    
    // ============================================
    // STUDENT - DS Creation, Play & View
    // ============================================
    // Create
    Route::get('/ds/create', [DSManagementController::class, 'create'])->name('ds.create');
    Route::post('/ds', [DSManagementController::class, 'store'])
        ->middleware('throttle:10,1') // 10 DS creation attempts per minute max
        ->name('ds.store');

    // View
    Route::get('/ds/myDS/{id}', [DSController::class, 'indexUser'])->name('ds.myDS');
    Route::get('/ds/{id}', [DSController::class, 'show'])->name('ds.show');

    // Play
    Route::get('/ds/{id}/start', [DSPlayController::class, 'start'])->name('ds.start');
    Route::get('/ds/{id}/pause/{timer}', [DSPlayController::class, 'pause'])->name('ds.pause');
    Route::get('/ds/{id}/finish', [DSPlayController::class, 'finish'])->name('ds.finish');
});
