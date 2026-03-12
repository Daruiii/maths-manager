<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refacto nomenclature :
     * - ds_exercises → problems
     * - ds_exercises_ds → ds_problem
     * - chapters_exercises_ds → chapter_problem
     */
    public function up(): void
    {
        // 1. Rename main table
        Schema::rename('ds_exercises', 'problems');

        // 2. Rename pivot DS ↔ Problem
        Schema::rename('ds_exercises_ds', 'ds_problem');

        // 3. Rename pivot Chapter ↔ Problem
        Schema::rename('chapters_exercises_ds', 'chapter_problem');

        // 4. Update foreign key column names in ds_problem
        Schema::table('ds_problem', function (Blueprint $table) {
            $table->renameColumn('ds_exercise_id', 'problem_id');
        });

        // 5. Update foreign key column names in chapter_problem
        Schema::table('chapter_problem', function (Blueprint $table) {
            $table->renameColumn('exercise_ds_id', 'problem_id');
        });
    }

    public function down(): void
    {
        // Reverse column renames first
        Schema::table('chapter_problem', function (Blueprint $table) {
            $table->renameColumn('problem_id', 'exercise_ds_id');
        });

        Schema::table('ds_problem', function (Blueprint $table) {
            $table->renameColumn('problem_id', 'ds_exercise_id');
        });

        // Reverse table renames
        Schema::rename('chapter_problem', 'chapters_exercises_ds');
        Schema::rename('ds_problem', 'ds_exercises_ds');
        Schema::rename('problems', 'ds_exercises');
    }
};
