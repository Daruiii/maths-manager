<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DS\DSController;
use App\Http\Controllers\DS\DSPlayController;
use App\Http\Controllers\DS\DSManagementController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsVerified;

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
    });
    
    // ============================================
    // STUDENT - DS Creation, Play & View
    // ============================================
    Route::middleware([IsVerified::class])->group(function () {
        // Create (students can create 1 DS per day)
        Route::get('/ds/create', [DSManagementController::class, 'create'])->name('ds.create');
        Route::post('/ds', [DSManagementController::class, 'store'])->name('ds.store');
        
        // View
        Route::get('/ds/myDS/{id}', [DSController::class, 'indexUser'])->name('ds.myDS');
        Route::get('/ds/{id}', [DSController::class, 'show'])->name('ds.show');
        
        // Play
        Route::get('/ds/{id}/start', [DSPlayController::class, 'start'])->name('ds.start');
        Route::get('/ds/{id}/pause/{timer}', [DSPlayController::class, 'pause'])->name('ds.pause');
        Route::get('/ds/{id}/finish', [DSPlayController::class, 'finish'])->name('ds.finish');
    });
});
