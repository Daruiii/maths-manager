<?php

use App\Http\Controllers\DM\DmController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| DM Routes (Devoirs Maison)
|--------------------------------------------------------------------------
|
| Routes côté élève pour les DM :
| - Affichage du DM
| - Mise à jour du status (not_started → ongoing)
| - Envoi de la copie pour correction
|
*/

Route::middleware('auth')->prefix('dm')->name('dm.')->group(function () {
    Route::get('/{dm}', [DmController::class, 'show'])->name('show');
    Route::patch('/{dm}/status', [DmController::class, 'updateStatus'])->name('status.update');
    Route::post('/{dm}/correction', [DmController::class, 'submitCorrection'])
        ->middleware('throttle:5,1')
        ->name('correction.submit');
});
