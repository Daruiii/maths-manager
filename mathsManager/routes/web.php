<?php

use App\Http\Controllers\Auth\ProviderController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\IsVerified;
use App\Http\Controllers\CorrectionRequestController;
use App\Http\Controllers\RecapController;

use App\Http\Controllers\ContentController;
use App\Http\Controllers\OrderingController;

Route::get('/', [HomeController::class, 'index'])->name('home.redirect');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Route pour robots.txt dynamique selon l'environnement
Route::get('/robots.txt', function () {
    if (config('app.env') === 'staging') {
        // En preprod, bloquer tout
        return response(file_get_contents(public_path('robots-preprod.txt')))
            ->header('Content-Type', 'text/plain');
    }
    
    // En production, utiliser le robots.txt standard (si il existe)
    if (file_exists(public_path('robots.txt'))) {
        return response(file_get_contents(public_path('robots.txt')))
            ->header('Content-Type', 'text/plain');
    }
    
    // Robots.txt par défaut (production)
    return response("User-agent: *\nDisallow: /admin\nDisallow: /login\n")
        ->header('Content-Type', 'text/plain');
})->name('robots');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [HomeController::class, 'admin'])->name('admin')->middleware(IsAdmin::class);
});

// errors
Route::get('/isntValid', [HomeController::class, 'isntValid'])->name('isntValid');

// Content routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/contents', [ContentController::class, 'index'])->name('contents.index');
        Route::get('/content/{section}/edit', [ContentController::class, 'edit'])->name('content.edit');
        Route::put('/content/{section}', [ContentController::class, 'update'])->name('content.update');
    });
});

// Ordering routes (nouveau système drag-and-drop multi-niveaux)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        // Déplacements entre conteneurs
        Route::post('/ordering/move-subchapter', [OrderingController::class, 'moveSubchapter'])->name('ordering.moveSubchapter');
        Route::post('/ordering/move-chapter', [OrderingController::class, 'moveChapter'])->name('ordering.moveChapter');
        Route::post('/ordering/move-class', [OrderingController::class, 'moveClass'])->name('ordering.moveClass');
        
        // Réorganisations internes
        Route::post('/ordering/reorder-subchapters', [OrderingController::class, 'reorderSubchaptersInChapter'])->name('ordering.reorderSubchapters');
        Route::post('/ordering/reorder-chapters', [OrderingController::class, 'reorderChaptersInClass'])->name('ordering.reorderChapters');
        Route::post('/ordering/reorder-classes', [OrderingController::class, 'reorderClasses'])->name('ordering.reorderClasses');
        
        // Preview d'impact
        Route::post('/ordering/preview-move', [OrderingController::class, 'previewMove'])->name('ordering.previewMove');
    });
});









// Récap routes
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/recap/create/{id}', [RecapController::class, 'create'])->name('recap.create');
        Route::post('/recap', [RecapController::class, 'store'])->name('recap.store');
        Route::delete('/recap/{id}', [RecapController::class, 'destroy'])->name('recap.destroy');
        //  routes récap part
        Route::get('/recap/{id}/createPart', [RecapController::class, 'createPart'])->name('recap.createPart');
        Route::post('/recapPart', [RecapController::class, 'storePart'])->name('recapPart.store');
        Route::get('/recapPart/{id}/edit', [RecapController::class, 'editPart'])->name('recapPart.edit');
        Route::patch('/recapPart/{id}', [RecapController::class, 'updatePart'])->name('recapPart.update');
        Route::delete('/recapPart/{id}', [RecapController::class, 'destroyPart'])->name('recapPart.destroy');
        // route récap block part
        Route::get('/recapPart/{id}/createBlock', [RecapController::class, 'createPartBlock'])->name('recapPartBlock.createBlock');
        Route::post('/recapPartBlock', [RecapController::class, 'storePartBlock'])->name('recapPartBlock.store');
        Route::get('/recapPartBlock/{id}/edit', [RecapController::class, 'editPartBlock'])->name('recapPartBlock.edit');
        Route::patch('/recapPartBlock/{id}', [RecapController::class, 'updatePartBlock'])->name('recapPartBlock.update');
        Route::delete('/recapPartBlock/{id}', [RecapController::class, 'destroyPartBlock'])->name('recapPartBlock.destroy');
        // ordering routes
        Route::post('/recapPart/{id}/moveUp', [RecapController::class, 'movePartUp'])->name('recapPart.moveUp');
        Route::post('/recapPart/{id}/moveDown', [RecapController::class, 'movePartDown'])->name('recapPart.moveDown');
        Route::post('/recapPartBlock/reorder', [RecapController::class, 'reorderBlocks'])->name('recapPartBlock.reorder');
        Route::post('/recapPartBlock/{id}/moveToPart', [RecapController::class, 'moveBlockToPart'])->name('recapPartBlock.moveToPart');
    });
    Route::middleware([IsVerified::class])->group(function () {
        Route::get('/recap/{id}', [RecapController::class, 'show'])->name('recap.show');
    });
});



// ============================================
// MODULAR ROUTES
// ============================================
require __DIR__.'/web/classes.php';
require __DIR__.'/web/chapters.php';
require __DIR__.'/web/exercises.php';
require __DIR__.'/web/sheets.php';
require __DIR__.'/web/ds.php';
require __DIR__.'/web/whitelist.php';

//CorrectionsRequest routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        /// index
        Route::get('/correctionRequest', [CorrectionRequestController::class, 'index'])->name('correctionRequest.index');
        // Route::get('/myCorrections', [CorrectionRequestController::class, 'myCorrections'])->name('correctionRequest.myCorrections');
        Route::get('/correctionRequest/correct/{ds_id}', [CorrectionRequestController::class, 'showCorrectionForm'])->name('correctionRequest.correctForm');
        Route::post('/correctionRequest/correct/{ds_id}', [CorrectionRequestController::class, 'correctCorrectionRequest'])->name('correctionRequest.correct');
        Route::delete('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'destroyCorrectionRequest'])->name('correctionRequest.destroy');
    });
    Route::middleware([IsVerified::class])->group(function () {
        Route::get('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'showCorrectionRequestForm'])->name('correctionRequest.showCorrectionRequestForm');
        Route::post('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'sendCorrectionRequest'])
            ->middleware('throttle:10,1') // 10 correction requests per minute max
            ->name('correctionRequest.sendCorrectionRequest');
        Route::get('/correctionRequest/show/{ds_id}', [CorrectionRequestController::class, 'showCorrectionRequest'])->name('correctionRequest.show');
        Route::get('/correctionRequest/edit/{ds_id}', [CorrectionRequestController::class, 'edit'])->name('correctionRequest.edit');
        Route::put('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'update'])->name('correctionRequest.update');
    });
});

require __DIR__.'/web/quizz.php';
require __DIR__.'/web/users.php';

// Socialite routes (connection with google)
Route::get('auth/{provider}/redirect', [ProviderController::class, 'redirect']);
Route::get('auth/{provider}/callback', [ProviderController::class, 'callback']);

// Private files routes (avec authentification)
Route::middleware('auth')->group(function () {
    Route::get('/private/{context}/{identifier}/{filename}', [\App\Http\Controllers\PrivateFileController::class, 'serve'])
        ->where('filename', '.*')
        ->name('private.file.serve');
});

require __DIR__ . '/auth.php';
