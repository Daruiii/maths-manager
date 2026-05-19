<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refonte complète du modèle TD pour le TD Builder :
     * - Suppression du legacy (chapter_id)
     * - Ajout teacher_id, user_id, batch_id, custom_title, custom_level, custom_instructions, correction_unlocked
     * - Nouvelle table td_batches
     * - Nouveau pivot td_private_exercises
     */
    public function up(): void
    {
        // 1. Table td_batches (même structure que ds_batches)
        if (!Schema::hasTable('td_batches')) {
            Schema::create('td_batches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
                $table->json('group_ids')->nullable();
                $table->json('student_ids');
                $table->unsignedInteger('td_count');
                $table->timestamps();
            });
        }

        // Drop legacy FK + colonne chapter_id si elle existe encore
        if (Schema::hasColumn('td', 'chapter_id')) {
            Schema::table('td', function (Blueprint $table) {
                try {
                    $table->dropForeign('exercises_sheet_chapter_id_foreign');
                } catch (\Throwable) {
                    try { $table->dropForeign(['chapter_id']); } catch (\Throwable) {}
                }
                $table->dropColumn('chapter_id');
            });
        }

        Schema::table('td', function (Blueprint $table) {

            if (!Schema::hasColumn('td', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('td', 'batch_id')) {
                $table->foreignId('batch_id')->nullable()->constrained('td_batches')->nullOnDelete();
            }
            if (!Schema::hasColumn('td', 'custom_title')) {
                $table->string('custom_title')->nullable();
            }
            if (!Schema::hasColumn('td', 'custom_level')) {
                $table->string('custom_level')->nullable();
            }
            if (!Schema::hasColumn('td', 'custom_instructions')) {
                $table->text('custom_instructions')->nullable();
            }
            if (!Schema::hasColumn('td', 'correction_unlocked')) {
                $table->boolean('correction_unlocked')->default(false);
            }
        });

        // 3. Pivot td_private_exercises
        if (!Schema::hasTable('td_private_exercises')) {
            Schema::create('td_private_exercises', function (Blueprint $table) {
                $table->foreignId('td_id')->constrained('td')->cascadeOnDelete();
                $table->foreignId('private_exercise_id')->constrained('private_exercises')->cascadeOnDelete();
                $table->primary(['td_id', 'private_exercise_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('td_private_exercises');

        Schema::table('td', function (Blueprint $table) {
            if (Schema::hasColumn('td', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                $table->dropColumn('teacher_id');
            }
            if (Schema::hasColumn('td', 'batch_id')) {
                $table->dropForeign(['batch_id']);
                $table->dropColumn('batch_id');
            }
            foreach (['custom_title', 'custom_level', 'custom_instructions', 'correction_unlocked'] as $col) {
                if (Schema::hasColumn('td', $col)) {
                    $table->dropColumn($col);
                }
            }

            if (!Schema::hasColumn('td', 'chapter_id')) {
                $table->unsignedBigInteger('chapter_id')->nullable();
                $table->foreign('chapter_id', 'exercises_sheet_chapter_id_foreign')
                    ->references('id')->on('chapters')->onDelete('set null');
            }
        });

        Schema::dropIfExists('td_batches');
    }
};
