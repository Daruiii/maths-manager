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
        Schema::table('ds_exercises', function (Blueprint $table) {
            // Ajouter colonne difficulty (1-5 étoiles), par défaut 3 (difficulté moyenne)
            $table->unsignedTinyInteger('difficulty')->default(3)->after('harder_exercise');
        });

        // Migration des données existantes: harder_exercise → difficulty
        DB::statement('UPDATE ds_exercises SET difficulty = CASE WHEN harder_exercise = 1 THEN 5 ELSE 3 END');

        // Supprimer l'ancienne colonne harder_exercise
        Schema::table('ds_exercises', function (Blueprint $table) {
            $table->dropColumn('harder_exercise');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ds_exercises', function (Blueprint $table) {
            // Restaurer harder_exercise
            $table->boolean('harder_exercise')->default(false)->after('multiple_chapter_id');
        });

        // Migration inverse: difficulty → harder_exercise (4-5 étoiles = difficile)
        DB::statement('UPDATE ds_exercises SET harder_exercise = CASE WHEN difficulty >= 4 THEN 1 ELSE 0 END');

        // Supprimer la colonne difficulty
        Schema::table('ds_exercises', function (Blueprint $table) {
            $table->dropColumn('difficulty');
        });
    }
};
