<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecapController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsVerified;

/*
|--------------------------------------------------------------------------
| Recap Routes
|--------------------------------------------------------------------------
|
| Routes pour la gestion des récapitulatifs (fiches de révision)
| Divisées en admin (CRUD) et student (lecture)
|
*/

Route::middleware(['auth', 'throttle:60,1'])->group(function () {

    // ============================================
    // ADMIN - Recap Management
    // ============================================
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        // Recap CRUD
        Route::get('/recap/create/{id}', [RecapController::class, 'create'])->name('recap.create');
        Route::post('/recap', [RecapController::class, 'store'])->name('recap.store');
        Route::delete('/recap/{id}', [RecapController::class, 'destroy'])->name('recap.destroy');

        // Recap Parts CRUD
        Route::get('/recap/{id}/createPart', [RecapController::class, 'createPart'])->name('recap.createPart');
        Route::post('/recapPart', [RecapController::class, 'storePart'])->name('recapPart.store');
        Route::get('/recapPart/{id}/edit', [RecapController::class, 'editPart'])->name('recapPart.edit');
        Route::patch('/recapPart/{id}', [RecapController::class, 'updatePart'])->name('recapPart.update');
        Route::delete('/recapPart/{id}', [RecapController::class, 'destroyPart'])->name('recapPart.destroy');

        // Recap Part Blocks CRUD
        Route::get('/recapPart/{id}/createBlock', [RecapController::class, 'createPartBlock'])->name('recapPartBlock.createBlock');
        Route::post('/recapPartBlock', [RecapController::class, 'storePartBlock'])->name('recapPartBlock.store');
        Route::get('/recapPartBlock/{id}/edit', [RecapController::class, 'editPartBlock'])->name('recapPartBlock.edit');
        Route::patch('/recapPartBlock/{id}', [RecapController::class, 'updatePartBlock'])->name('recapPartBlock.update');
        Route::delete('/recapPartBlock/{id}', [RecapController::class, 'destroyPartBlock'])->name('recapPartBlock.destroy');

        // Ordering
        Route::post('/recapPart/{id}/moveUp', [RecapController::class, 'movePartUp'])->name('recapPart.moveUp');
        Route::post('/recapPart/{id}/moveDown', [RecapController::class, 'movePartDown'])->name('recapPart.moveDown');
        Route::post('/recapPartBlock/reorder', [RecapController::class, 'reorderBlocks'])->name('recapPartBlock.reorder');
        Route::post('/recapPartBlock/{id}/moveToPart', [RecapController::class, 'moveBlockToPart'])->name('recapPartBlock.moveToPart');
    });

    // ============================================
    // STUDENT - Recap View
    // ============================================
    Route::middleware([IsVerified::class])->group(function () {
        Route::get('/recap/{id}', [RecapController::class, 'show'])->name('recap.show');
    });
});
