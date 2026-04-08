<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── dm_batches ─────────────────────────────────────────────────────────
        Schema::create('dm_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->json('group_ids')->nullable();
            $table->json('student_ids');
            $table->unsignedInteger('dm_count')->default(0);
            $table->timestamps();
        });

        // ── dm ─────────────────────────────────────────────────────────────────
        Schema::create('dm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('dm_batches')->nullOnDelete();
            $table->enum('status', ['not_started', 'ongoing', 'finished', 'corrected'])->default('not_started');
            $table->string('custom_title')->nullable();
            $table->string('custom_level')->nullable();
            $table->text('custom_instructions')->nullable();
            $table->timestamps();
        });

        // ── Pivots ─────────────────────────────────────────────────────────────
        Schema::create('dm_problem', function (Blueprint $table) {
            $table->foreignId('dm_id')->constrained('dm')->cascadeOnDelete();
            $table->foreignId('problem_id')->constrained('problems')->cascadeOnDelete();
            $table->primary(['dm_id', 'problem_id']);
        });

        Schema::create('dm_exercise', function (Blueprint $table) {
            $table->foreignId('dm_id')->constrained('dm')->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
            $table->primary(['dm_id', 'exercise_id']);
        });

        Schema::create('dm_private_exercise', function (Blueprint $table) {
            $table->foreignId('dm_id')->constrained('dm')->cascadeOnDelete();
            $table->foreignId('private_exercise_id')->constrained('private_exercises')->cascadeOnDelete();
            $table->primary(['dm_id', 'private_exercise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dm_private_exercise');
        Schema::dropIfExists('dm_exercise');
        Schema::dropIfExists('dm_problem');
        Schema::dropIfExists('dm');
        Schema::dropIfExists('dm_batches');
    }
};
