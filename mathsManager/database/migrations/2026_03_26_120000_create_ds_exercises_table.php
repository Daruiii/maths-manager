<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ds_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ds_id')->constrained('ds')->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['ds_id', 'exercise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ds_exercises');
    }
};
