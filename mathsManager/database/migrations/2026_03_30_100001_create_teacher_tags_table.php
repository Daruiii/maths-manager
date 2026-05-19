<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 50);
            $table->string('color', 7)->nullable(); // #rrggbb optionnel
            $table->timestamps();

            $table->unique(['teacher_id', 'name']);
        });

        Schema::create('private_exercise_teacher_tag', function (Blueprint $table) {
            $table->foreignId('private_exercise_id')
                ->constrained('private_exercises')
                ->cascadeOnDelete();
            $table->foreignId('teacher_tag_id')
                ->constrained('teacher_tags')
                ->cascadeOnDelete();
            $table->primary(['private_exercise_id', 'teacher_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('private_exercise_teacher_tag');
        Schema::dropIfExists('teacher_tags');
    }
};
