<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('private_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['basic', 'problem']);
            $table->string('name');
            $table->text('statement')->nullable();
            $table->text('latex_statement')->nullable();
            $table->text('solution')->nullable();
            $table->text('latex_solution')->nullable();
            $table->text('clue')->nullable();
            $table->text('latex_clue')->nullable();
            $table->unsignedTinyInteger('difficulty')->nullable();
            $table->unsignedSmallInteger('time')->nullable()->comment('Durée estimée en minutes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('private_exercises');
    }
};
