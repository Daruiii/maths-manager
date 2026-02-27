<?php

use App\Http\Controllers\Admin\TeacherApplicationController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/applications', [TeacherApplicationController::class, 'index'])->name('applications.index');
    Route::post('/applications/{user}/approve', [TeacherApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{user}/invite', [TeacherApplicationController::class, 'invite'])->name('applications.invite');
    Route::post('/applications/{user}/reject', [TeacherApplicationController::class, 'reject'])->name('applications.reject');
    Route::post('/applications/{user}/ban', [TeacherApplicationController::class, 'ban'])->name('applications.ban');
});
