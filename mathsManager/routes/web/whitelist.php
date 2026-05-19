<?php

use App\Http\Controllers\Whitelist\WhitelistRequestController;
use App\Http\Controllers\Whitelist\ExerciseWhitelistController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

// ============================================
// WHITELIST ROUTES
// ============================================

Route::middleware('auth')->group(function () {
    // Admin routes - manage whitelists and requests
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        // Exercise whitelist management (admin)
        Route::get('/exercise/{exerciseId}/whitelist', [ExerciseWhitelistController::class, 'show'])->name('exercise.whitelist.show');
        Route::post('/exercise/{exerciseId}/whitelist', [ExerciseWhitelistController::class, 'addStudent'])->name('exercise.whitelist.add');
        Route::delete('/exercise/{exerciseId}/whitelist/{userId}', [ExerciseWhitelistController::class, 'removeStudent'])->name('exercise.whitelist.remove');
        
        // Whitelist requests management (admin)
        Route::get('/whitelist-requests', [WhitelistRequestController::class, 'index'])->name('whitelist-requests.index');
        Route::post('/whitelist-requests/{requestId}/approve', [WhitelistRequestController::class, 'approve'])->name('whitelist-requests.approve');
        Route::post('/whitelist-requests/{requestId}/reject', [WhitelistRequestController::class, 'reject'])->name('whitelist-requests.reject');
        Route::delete('/whitelist-requests/{requestId}', [WhitelistRequestController::class, 'destroy'])->name('whitelist-requests.destroy');
        Route::delete('/whitelist-requests/clear-history', [WhitelistRequestController::class, 'clearHistory'])->name('whitelist-requests.clear-history');
    });
    
    // Student routes - submit and view own requests
    Route::post('/exercise/{exerciseId}/request-whitelist', [WhitelistRequestController::class, 'store'])
        ->middleware('throttle:5,1') // 5 whitelist requests per minute max
        ->name('whitelist-request.store');
    Route::get('/my-whitelist-requests', [WhitelistRequestController::class, 'myRequests'])->name('whitelist-requests.my');
});
