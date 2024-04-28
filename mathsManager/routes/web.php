<?php

use App\Http\Controllers\Auth\ProviderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\SubchapterController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\DsExerciseController;
use App\Http\Controllers\MultipleChapterController;
use App\Http\Controllers\DSController;
use App\Http\Middleware\IsVerified;
use App\Http\Controllers\CorrectionRequestController;


Route::get('/', function () {
    return view('home');
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
// route for use changeAnalyse2Color function
Route::get('/changeAnalyse1Color', [ChapterController::class, 'changeAnalyse1Color'])->name('changeAnalyse1Color');
Route::middleware('auth')->group(function () {
    Route::get('/admin', [HomeController::class, 'admin'])->name('admin')->middleware(IsAdmin::class);
});

// errors
Route::get('/isntValid', [HomeController::class, 'isntValid'])->name('isntValid');

// Classe routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/classe', [ClasseController::class, 'index'])->name('classe.index');
        Route::get('/classe/create', [ClasseController::class, 'create'])->name('classe.create');
        Route::post('/classe', [ClasseController::class, 'store'])->name('classe.store');
        Route::get('/classe/{id}/edit', [ClasseController::class, 'edit'])->name('classe.edit');
        Route::patch('/classe/{id}', [ClasseController::class, 'update'])->name('classe.update');
        Route::delete('/classe/{id}', [ClasseController::class, 'destroy'])->name('classe.destroy');
    });
});
Route::get('/classe/{level}', [ClasseController::class, 'show'])->name('classe.show');

// Chapter routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/chapter', [ChapterController::class, 'index'])->name('chapter.index');
        Route::get('/chapter/{id}/create', [ChapterController::class, 'create'])->name('chapter.create');
        Route::post('/chapter', [ChapterController::class, 'store'])->name('chapter.store');
        Route::get('/chapter/{id}/edit', [ChapterController::class, 'edit'])->name('chapter.edit');
        Route::patch('/chapter/{id}', [ChapterController::class, 'update'])->name('chapter.update');
        Route::delete('/chapter/{id}', [ChapterController::class, 'destroy'])->name('chapter.destroy');
    });
});
Route::get('/chapter/{id}', [ChapterController::class, 'show'])->name('chapter.show');

// Subchapter routes
Route::get('/subchapter', [SubchapterController::class, 'index'])->name('subchapter.index');
Route::middleware([IsAdmin::class])->group(function () {
    Route::get('/subchapter/{id}/create', [SubchapterController::class, 'create'])->name('subchapter.create');
    Route::post('/subchapter', [SubchapterController::class, 'store'])->name('subchapter.store');
    Route::get('/subchapter/{id}/edit', [SubchapterController::class, 'edit'])->name('subchapter.edit');
    Route::patch('/subchapter/{id}', [SubchapterController::class, 'update'])->name('subchapter.update');
    Route::delete('/subchapter/{id}', [SubchapterController::class, 'destroy'])->name('subchapter.destroy');
});
Route::get('/subchapter/{id}', [SubchapterController::class, 'show'])->name('subchapter.show');

// Exercise routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/exercise/{id}/create', [ExerciseController::class, 'create'])->name('exercise.create');
        Route::post('/exercise', [ExerciseController::class, 'store'])->name('exercise.store');
        Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
        Route::get('/exercise/{id}', [ExerciseController::class, 'show'])->name('exercise.show');
        Route::get('/exercise/{id}/edit', [ExerciseController::class, 'edit'])->name('exercise.edit');
        Route::patch('/exercise/{id}', [ExerciseController::class, 'update'])->name('exercise.update');
        Route::delete('/exercise/{id}', [ExerciseController::class, 'destroy'])->name('exercise.destroy');
    });
});

// DS_Exercise routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/ds_exercise/create', [DsExerciseController::class, 'create'])->name('ds_exercise.create');
        Route::post('/ds_exercise', [DsExerciseController::class, 'store'])->name('ds_exercise.store');
        Route::get('/ds_exercises', [DsExerciseController::class, 'index'])->name('ds_exercises.index');
        Route::get('/ds_exercise/{id}', [DsExerciseController::class, 'show'])->name('ds_exercise.show');
        Route::get('/ds_exercise/{id}/edit', [DsExerciseController::class, 'edit'])->name('ds_exercise.edit');
        Route::patch('/ds_exercise/{id}', [DsExerciseController::class, 'update'])->name('ds_exercise.update');
        Route::delete('/ds_exercise/{id}', [DsExerciseController::class, 'destroy'])->name('ds_exercise.destroy');
    });
});

// Multiple_chapters routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/multiple_chapters/create', [MultipleChapterController::class, 'create'])->name('multiple_chapter.create');
        Route::post('/multiple_chapters', [MultipleChapterController::class, 'store'])->name('multiple_chapter.store');
        Route::get('/multiple_chapters', [MultipleChapterController::class, 'index'])->name('multiple_chapters.index');
        Route::get('/multiple_chapters/{id}', [MultipleChapterController::class, 'show'])->name('multiple_chapters.show');
        Route::get('/multiple_chapters/{id}/edit', [MultipleChapterController::class, 'edit'])->name('multiple_chapter.edit');
        Route::patch('/multiple_chapters/{id}', [MultipleChapterController::class, 'update'])->name('multiple_chapter.update');
        Route::delete('/multiple_chapters/{id}', [MultipleChapterController::class, 'destroy'])->name('multiple_chapter.destroy');
    });
});

// DS routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/ds', [DSController::class, 'index'])->name('ds.index');
        Route::get('/ds/{id}/edit', [DSController::class, 'edit'])->name('ds.edit');
        Route::patch('/ds/{id}', [DSController::class, 'update'])->name('ds.update');
    });
    Route::middleware([IsVerified::class])->group(function () {
        Route::get('/ds/create', [DSController::class, 'create'])->name('ds.create');
        Route::post('/ds', [DSController::class, 'store'])->name('ds.store');
        Route::get('/ds/myDS/{id}', [DSController::class, 'indexUser'])->name('ds.myDS');
        Route::delete('/ds/{id}', [DSController::class, 'destroy'])->name('ds.destroy');      
        Route::get('/ds/{id}', [DSController::class, 'show'])->name('ds.show');
        Route::get('/ds/{id}/start', [DSController::class, 'start'])->name('ds.start');
        Route::get('/ds/{id}/pause/{timer}', [DSController::class, 'pause'])->name('ds.pause');
        Route::get('/ds/{id}/finish', [DSController::class, 'finish'])->name('ds.finish');
    });
});

//CorrectionsRequest routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
    /// index
    Route::get('/correctionRequest', [CorrectionRequestController::class, 'index'])->name('correctionRequest.index');
    Route::get('/correctionRequest/correct/{ds_id}', [CorrectionRequestController::class, 'showCorrectionForm'])->name('correctionRequest.correctForm');
    Route::post('/correctionRequest/correct/{ds_id}', [CorrectionRequestController::class, 'correctCorrectionRequest'])->name('correctionRequest.correct');
    Route::delete('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'destroyCorrectionRequest'])->name('correctionRequest.destroy');
    });
    Route::middleware([IsVerified::class])->group(function () {
    Route::get('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'showCorrectionRequestForm'])->name('correctionRequest.showCorrectionRequestForm');
    Route::post('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'sendCorrectionRequest'])->name('correctionRequest.sendCorrectionRequest');
    Route::get('/correctionRequest/show/{ds_id}', [CorrectionRequestController::class, 'showCorrectionRequest'])->name('correctionRequest.show');
    });
});

// Users routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::patch('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        // verify et unverify
        Route::patch('/user/{id}/verify', [UserController::class, 'verify'])->name('user.verify');
        Route::patch('/user/{id}/unverify', [UserController::class, 'unverify'])->name('user.unverify');
    });
});

// Socialite routes (connection with google)
Route::get('auth/{provider}/redirect', [ProviderController::class, 'redirect']);
Route::get('auth/{provider}/callback', [ProviderController::class, 'callback']);

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
