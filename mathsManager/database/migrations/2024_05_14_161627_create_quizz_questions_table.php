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
        Schema::create('quizz_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('latex_question')->nullable();
            $table->text('explanation')->nullable();
            $table->text('latex_explanation')->nullable();
            $table->foreignId('chapter_id')->constrained('chapters')->onDelete('cascade');
            $table->foreignId('subchapter_id')->constrained('subchapters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizz_questions');
    }
};
