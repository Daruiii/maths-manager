<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refacto nomenclature :
     * - exercises_sheet → td
     * - exercises_sheet_exercises → td_exercise
     */
    public function up(): void
    {
        // 1. Rename main table
        Schema::rename('exercises_sheet', 'td');

        // 2. Rename pivot TD ↔ Exercise
        Schema::rename('exercises_sheet_exercises', 'td_exercise');

        // 3. Update foreign key column name in td_exercise
        Schema::table('td_exercise', function (Blueprint $table) {
            $table->renameColumn('exercises_sheet_id', 'td_id');
        });
    }

    public function down(): void
    {
        // Reverse column rename first
        Schema::table('td_exercise', function (Blueprint $table) {
            $table->renameColumn('td_id', 'exercises_sheet_id');
        });

        // Reverse table renames
        Schema::rename('td_exercise', 'exercises_sheet_exercises');
        Schema::rename('td', 'exercises_sheet');
    }
};
