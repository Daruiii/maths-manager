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
        Schema::create('quizz_answers', function (Blueprint $table) {
            $table->id();
            $table->text('answer');
            $table->text('latex_answer')->nullable();
            $table->boolean('is_correct');
            $table->foreignId('quizz_question_id')->constrained('quizz_questions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizz_answers');
    }
};
