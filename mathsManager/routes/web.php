<?php

use App\Http\Controllers\Auth\ProviderController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

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

// ============================================
// MODULAR ROUTES
// ============================================
require __DIR__.'/web/classes.php';
require __DIR__.'/web/chapters.php';
require __DIR__.'/web/exercises.php';
require __DIR__.'/web/sheets.php';
require __DIR__.'/web/ds.php';
require __DIR__.'/web/whitelist.php';
require __DIR__.'/web/recap.php';
require __DIR__.'/web/corrections.php';
require __DIR__.'/web/quizz.php';
require __DIR__.'/web/users.php';
require __DIR__.'/web/admin.php';
require __DIR__.'/web/teacher.php';


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
require __DIR__ . '/web/onboarding.php';
