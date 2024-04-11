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
             // Supprimez la contrainte de clé étrangère existante
             $table->dropForeign(['multiple_chapter_id']);

             // Ajoutez la nouvelle contrainte avec l'option onDelete('cascade')
             $table->foreign('multiple_chapter_id')
                   ->references('id')->on('multiple_chapters')
                   ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ds_exercises', function (Blueprint $table) {
            // Supprimez la contrainte de clé étrangère existante
            $table->dropForeign(['multiple_chapter_id']);

            // Ajoutez la nouvelle contrainte sans l'option onDelete('cascade')
            $table->foreign('multiple_chapter_id')
                  ->references('id')->on('multiple_chapters');
        });
    }
};
