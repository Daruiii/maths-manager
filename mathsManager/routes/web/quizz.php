<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Quizz\QuizzPlayController;
use App\Http\Controllers\Quizz\QuizzQuestionController;
use App\Http\Controllers\Quizz\QuizzAnswerController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsVerified;

/*
|--------------------------------------------------------------------------
| Quizz Routes
|--------------------------------------------------------------------------
|
| Routes pour la gestion des quiz (questions, réponses, jeu)
| Divisées en 3 contrôleurs selon les responsabilités :
| - QuizzPlayController : Flux de jeu (étudiant)
| - QuizzQuestionController : CRUD questions (admin/teacher)
| - QuizzAnswerController : CRUD réponses (admin/teacher)
|
*/

Route::middleware('auth')->group(function () {
    
    // ============================================
    // ADMIN - Questions Management
    // ============================================
    Route::middleware([IsAdmin::class])->prefix('admin')->group(function () {
        // Questions CRUD
        Route::get('/quizz', [QuizzQuestionController::class, 'index'])->name('quizz.index');
        Route::get('/quizz/create', [QuizzQuestionController::class, 'create'])->name('quizz.create');
        Route::post('/quizz', [QuizzQuestionController::class, 'store'])->name('quizz.store');
        Route::get('/quizz/{id}', [QuizzQuestionController::class, 'show'])->name('quizz.show');
        Route::get('/quizz/edit/{id}', [QuizzQuestionController::class, 'edit'])->name('quizz.edit');
        Route::put('/quizz/{id}', [QuizzQuestionController::class, 'update'])->name('quizz.update');
        Route::delete('/quizz/{id}', [QuizzQuestionController::class, 'destroy'])->name('quizz.destroy');
        Route::post('/questions/{id}/duplicate', [QuizzQuestionController::class, 'duplicate'])->name('duplicate_question');
        
        // Answers CRUD
        Route::get('/quizz/{id}/answer/create', [QuizzAnswerController::class, 'create'])->name('quizz.answer.create');
        Route::post('/quizz/{id}/answer', [QuizzAnswerController::class, 'store'])->name('quizz.answer.store');
        Route::get('/quizz/answer/edit/{id}', [QuizzAnswerController::class, 'edit'])->name('quizz.answer.edit');
        Route::put('/quizz/answer/{id}', [QuizzAnswerController::class, 'update'])->name('quizz.answer.update');
        Route::delete('/quizz/answer/{id}', [QuizzAnswerController::class, 'destroy'])->name('quizz.answer.destroy');
    });
    
    // ============================================
    // STUDENT - Quiz Play
    // ============================================
    Route::middleware([IsVerified::class])->group(function () {
        Route::get('/quizz/start/{chapter_id}', [QuizzPlayController::class, 'start'])->name('start_quizz');
        Route::get('/myQuizz', [QuizzPlayController::class, 'showQuestion'])->name('show_question');
        Route::post('/quizz/check', [QuizzPlayController::class, 'checkAnswer'])->name('check_answer');
        Route::get('/quizz/answer/{answer_id}/{correct_answer}', [QuizzPlayController::class, 'showAnswer'])->name('show_answer');
        Route::get('/quizzResult', [QuizzPlayController::class, 'showResult'])->name('show_result');
        Route::get('/quizzEnd', [QuizzPlayController::class, 'end'])->name('end_quizz');
    });
});
