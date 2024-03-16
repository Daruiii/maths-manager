<?php

use App\Http\Controllers\Auth\ProviderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\SubchapterController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Classe routes
Route::get('/classe', [ClasseController::class, 'index'])->name('classe.index');
Route::middleware([IsAdmin::class])->group(function () {
    Route::get('/classe/create', [ClasseController::class, 'create'])->name('classe.create');
    Route::post('/classe', [ClasseController::class, 'store'])->name('classe.store');
    Route::get('/classe/{id}/edit', [ClasseController::class, 'edit'])->name('classe.edit');
    Route::patch('/classe/{id}', [ClasseController::class, 'update'])->name('classe.update');
    Route::delete('/classe/{id}', [ClasseController::class, 'destroy'])->name('classe.destroy');
});
Route::get('/classe/{level}', [ClasseController::class, 'show'])->name('classe.show');

// Chapter routes
Route::get('/chapter', [ChapterController::class, 'index'])->name('chapter.index');
Route::middleware([IsAdmin::class])->group(function () {
Route::get('/chapter/create', [ChapterController::class, 'create'])->name('chapter.create');
Route::post('/chapter', [ChapterController::class, 'store'])->name('chapter.store');
Route::get('/chapter/{id}/edit', [ChapterController::class, 'edit'])->name('chapter.edit');
Route::patch('/chapter/{id}', [ChapterController::class, 'update'])->name('chapter.update');
Route::delete('/chapter/{id}', [ChapterController::class, 'destroy'])->name('chapter.destroy');
});
Route::get('/chapter/{id}', [ChapterController::class, 'show'])->name('chapter.show');

// Subchapter routes
Route::get('/subchapter', [SubchapterController::class, 'index'])->name('subchapter.index');
Route::get('/subchapter/{id}', [SubchapterController::class, 'show'])->name('subchapter.show');
Route::get('/subchapter/create', [SubchapterController::class, 'create'])->name('subchapter.create');
Route::post('/subchapter', [SubchapterController::class, 'store'])->name('subchapter.store');
Route::get('/subchapter/{id}/edit', [SubchapterController::class, 'edit'])->name('subchapter.edit');
Route::patch('/subchapter/{id}', [SubchapterController::class, 'update'])->name('subchapter.update');
Route::delete('/subchapter/{id}', [SubchapterController::class, 'destroy'])->name('subchapter.destroy');

// Socialite routes (connection with google)
Route::get('auth/{provider}/redirect', [ProviderController::class, 'redirect']);
Route::get('auth/{provider}/callback', [ProviderController::class, 'callback']);

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
