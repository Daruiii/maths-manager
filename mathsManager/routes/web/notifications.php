<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/{id}/redirect', [NotificationController::class, 'redirect'])->name('redirect');
    Route::post('/read-all', [NotificationController::class, 'readAll'])->name('readAll');
});
