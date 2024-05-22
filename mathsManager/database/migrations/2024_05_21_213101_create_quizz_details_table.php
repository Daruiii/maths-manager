<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quizz_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quizz_id')->constrained('quizzes');
            $table->foreignId('question_id')->constrained('quizz_questions');
            $table->foreignId('chosen_answer_id')->constrained('quizz_answers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizz_details');
    }
};
