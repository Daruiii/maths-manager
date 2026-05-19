<?php

use App\Http\Controllers\TemporaryUploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Temporary Upload Routes
|--------------------------------------------------------------------------
|
| Session d'upload temporaire pour les corrections (copie élève / correction prof).
| - Créer une session nécessite d'être authentifié.
| - Ajouter / lister des fichiers fonctionne uniquement par token (sans auth)
|   pour permettre l'upload depuis un QR mobile.
| - Supprimer un fichier requiert d'être authentifié (seul le desktop supprime).
|
*/

Route::middleware('auth')->post(
    '/uploads/sessions',
    [TemporaryUploadController::class, 'createSession']
)->name('uploads.sessions.create');

Route::prefix('uploads/{token}')->name('uploads.')->group(function () {
    Route::get('/', [TemporaryUploadController::class, 'mobilePage'])->name('mobile');
    Route::get('/files', [TemporaryUploadController::class, 'listFiles'])->name('files.list');
    Route::post('/files', [TemporaryUploadController::class, 'addFile'])
        ->middleware('throttle:30,1')
        ->name('files.add');
    Route::delete('/files/{upload}', [TemporaryUploadController::class, 'deleteFile'])
        ->middleware('auth')
        ->name('files.delete');
});
