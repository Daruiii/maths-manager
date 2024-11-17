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
use App\Http\Controllers\ExercisesSheetController;
use App\Http\Controllers\DsExerciseController;
use App\Http\Controllers\MultipleChapterController;
use App\Http\Controllers\DSController;
use App\Http\Middleware\IsVerified;
use App\Http\Controllers\CorrectionRequestController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\QuizzController;
use App\Http\Controllers\ContentController;

Route::get('/', function () {
    return view('home');
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
// route for use changeAnalyse2Color function
Route::get('/changeAnalyse2Color', [MultipleChapterController::class, 'changeAnalyse2Color'])->name('changeAnalyse2Color');
Route::get('/changeSuitesColor', [MultipleChapterController::class, 'changeSuitesColor'])->name('changeSuitesColor');
Route::get('/changeAnalyse1Color', [MultipleChapterController::class, 'changeAnalyse1Color'])->name('changeAnalyse1Color');
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

// Classe routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/classe/reorder', [ClasseController::class, 'reorderAllElements'])->name('classe.reorder');
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
Route::middleware('auth')->prefix('admin')->group(function () {
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
Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/subchapter/{id}/create', [SubchapterController::class, 'create'])->name('subchapter.create');
    Route::post('/subchapter', [SubchapterController::class, 'store'])->name('subchapter.store');
    Route::get('/subchapter/{id}/edit', [SubchapterController::class, 'edit'])->name('subchapter.edit');
    Route::patch('/subchapter/{id}', [SubchapterController::class, 'update'])->name('subchapter.update');
    Route::delete('/subchapter/{id}', [SubchapterController::class, 'destroy'])->name('subchapter.destroy');
});
Route::get('/subchapter/{id}', [SubchapterController::class, 'show'])->name('subchapter.show');

// Exercise routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/exercise/{id}/create', [ExerciseController::class, 'create'])->name('exercise.create');
        Route::post('/exercise', [ExerciseController::class, 'store'])->name('exercise.store');
        Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
        Route::get('/exercises/decrement', [ExerciseController::class, 'decrementAllExercises'])->name('exercises.decrement');
        Route::post('/exercises/update-order', [ExerciseController::class, 'updateOrder'])->name('exercises.updateOrder');
        Route::get('/exercise/{id}', [ExerciseController::class, 'show'])->name('exercise.show');
        Route::get('/exercise/{id}/edit', [ExerciseController::class, 'edit'])->name('exercise.edit');
        Route::patch('/exercise/{id}', [ExerciseController::class, 'update'])->name('exercise.update');
        Route::delete('/exercise/{id}', [ExerciseController::class, 'destroy'])->name('exercise.destroy');
    });
});

// ExerciseSheet routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/exercises_sheet/create', [ExercisesSheetController::class, 'create'])->name('exercises_sheet.create');
        Route::get('/exercises_sheet/select-chapter', [ExercisesSheetController::class, 'selectChapter'])->name('exercises_sheet.selectChapter');
        Route::post('/exercises_sheet', [ExercisesSheetController::class, 'store'])->name('exercises_sheet.store');
        Route::get('/exercises_sheets', [ExercisesSheetController::class, 'index'])->name('exercises_sheet.index');
        Route::get('/exercises_sheet/{id}/edit', [ExercisesSheetController::class, 'edit'])->name('exercises_sheet.edit');
        Route::patch('/exercises_sheet/{id}', [ExercisesSheetController::class, 'update'])->name('exercises_sheet.update');
        Route::delete('/exercises_sheet/{id}', [ExercisesSheetController::class, 'destroy'])->name('exercises_sheet.destroy');
    });
    Route::middleware([IsVerified::class])->group(function () {
        Route::get('/exercises_sheet/{id}', [ExercisesSheetController::class, 'show'])->name('exercises_sheet.show');
        Route::get('/exercises_sheet/myExercisesSheets/{id}', [ExercisesSheetController::class, 'indexUser'])->name('exercises_sheet.myExercisesSheets');
    });
});

// Récap routes
Route::middleware('auth')->group(function () {
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
    });
    Route::middleware([IsVerified::class])->group(function () {
        Route::get('/recap/{id}', [RecapController::class, 'show'])->name('recap.show');
    });
});

// DS_Exercise routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/ds_exercise/create', [DsExerciseController::class, 'create'])->name('ds_exercise.create');
        Route::post('/ds_exercise', [DsExerciseController::class, 'store'])->name('ds_exercise.store');
        Route::get('/ds_exercises', [DsExerciseController::class, 'index'])->name('ds_exercises.index');
        Route::get('/ds_exercise/{id}/edit/{filter}', [DsExerciseController::class, 'edit'])->name('ds_exercise.edit');
        Route::get('/ds_exercise/{id}/filter/{filter}', [DsExerciseController::class, 'show'])->name('ds_exercise.show');
        Route::patch('/ds_exercise/{id}', [DsExerciseController::class, 'update'])->name('ds_exercise.update');
        Route::delete('/ds_exercise/{id}', [DsExerciseController::class, 'destroy'])->name('ds_exercise.destroy');
    });
});

// Multiple_chapters routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
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
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/ds', [DSController::class, 'index'])->name('ds.index');
        Route::get('/ds/assign', [DSController::class, 'assignDS'])->name('ds.assign');
        Route::post('/ds/assign', [DSController::class, 'assignDS'])->name('ds.assign.store');
        Route::get('/ds/reAssign/{id}', [DSController::class, 'reAssignForm'])->name('ds.reAssignForm');
        Route::post('/ds/reAssign', [DSController::class, 'reAssign'])->name('ds.reAssign');
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
        Route::post('/correctionRequest/{ds_id}', [CorrectionRequestController::class, 'sendCorrectionRequest'])->name('correctionRequest.sendCorrectionRequest');
        Route::get('/correctionRequest/show/{ds_id}', [CorrectionRequestController::class, 'showCorrectionRequest'])->name('correctionRequest.show');
    });
});

// Quizzes routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/quizz', [QuizzController::class, 'index'])->name('quizz.index');
        Route::get('/quizz/create', [QuizzController::class, 'createQuestion'])->name('quizz.create');
        Route::post('/quizz', [QuizzController::class, 'storeQuestion'])->name('quizz.store');
        Route::get('/quizz/edit/{id}', [QuizzController::class, 'editQuestion'])->name('quizz.edit');
        Route::put('/quizz/{id}', [QuizzController::class, 'updateQuestion'])->name('quizz.update');
        Route::delete('/quizz/{id}', [QuizzController::class, 'destroyQuestion'])->name('quizz.destroy');
        Route::get('/quizz/{id}/answer/create', [QuizzController::class, 'createAnswer'])->name('quizz.answer.create');
        Route::post('/quizz/{id}/answer', [QuizzController::class, 'storeAnswer'])->name('quizz.answer.store');
        Route::get('/quizz/answer/edit/{id}', [QuizzController::class, 'editAnswer'])->name('quizz.answer.edit');
        Route::put('/quizz/answer/{id}', [QuizzController::class, 'updateAnswer'])->name('quizz.answer.update');
        Route::delete('/quizz/answer/{id}', [QuizzController::class, 'destroyAnswer'])->name('quizz.answer.destroy');
        Route::get('/quizz/{id}', [QuizzController::class, 'show'])->name('quizz.show');
        Route::post('/questions/{id}/duplicate', [QuizzController::class, 'duplicateQuestion'])->name('duplicate_question');
    });

    Route::middleware([IsVerified::class])->group(function () {
        Route::get('/myQuizz', [QuizzController::class, 'showQuestion'])->name('show_question');
        Route::post('/quizz/check', [QuizzController::class, 'checkAnswer'])->name('check_answer');
        Route::get('/quizzResult', [QuizzController::class, 'showResult'])->name('show_result');
        Route::get('/quizzEnd', [QuizzController::class, 'endQuizz'])->name('end_quizz');
        Route::get('/quizz/start/{chapter_id}', [QuizzController::class, 'startQuizz'])->name('start_quizz');
        Route::get('/quizz/answer/{answer_id}/{correct_answer}', [QuizzController::class, 'showAnswer'])->name('show_answer');
    });
});

// Users routes
Route::middleware('auth')->group(function () {
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/students', [UserController::class, 'showStudents'])->name('students.show');
        Route::get('/user/{student_id}/quizzes', [UserController::class, 'showQuizzes'])->name('student.quizzes');
        Route::get('/user/quizzes/{quiz_id}/details', [UserController::class, 'showQuizDetails'])->name('student.quizDetails');
        Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::patch('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        // verify et unverify
        Route::patch('/user/{id}/verify', [UserController::class, 'verify'])->name('user.verify');
        Route::patch('/user/{id}/unverify', [UserController::class, 'unverify'])->name('user.unverify');
        // reset last_ds_generated_at
        Route::patch('/user/{id}/resetLastDSGeneratedAt', [UserController::class, 'resetLastDSGeneratedAt'])->name('user.resetLastDSGeneratedAt');
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
