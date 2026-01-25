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
            // Modifier la colonne difficulty pour être nullable
            $table->unsignedTinyInteger('difficulty')->nullable()->change();
        });

        // Mettre à NULL tous les exercices qui ont difficulty = 3 (valeur par défaut)
        // Cela permet au prof de voir quels exercices n'ont pas encore été évalués
        DB::statement('UPDATE ds_exercises SET difficulty = NULL WHERE difficulty = 3');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre les NULL à 3 avant de rendre la colonne non-nullable
        DB::statement('UPDATE ds_exercises SET difficulty = 3 WHERE difficulty IS NULL');

        Schema::table('ds_exercises', function (Blueprint $table) {
            $table->unsignedTinyInteger('difficulty')->default(3)->change();
        });
    }
};
