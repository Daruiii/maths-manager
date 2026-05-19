<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('private_exercises', function (Blueprint $table) {
            // Liaison optionnelle au catalogue global (le prof peut choisir n'importe quel niveau)
            // Chaque FK est indépendante — pas de contrainte de cohérence forcée
            $table->foreignId('classe_id')
                ->nullable()->after('teacher_id')
                ->constrained('classes')->nullOnDelete();

            $table->foreignId('chapter_id')
                ->nullable()->after('classe_id')
                ->constrained('chapters')->nullOnDelete();

            $table->foreignId('subchapter_id')
                ->nullable()->after('chapter_id')
                ->constrained('subchapters')->nullOnDelete();

            // Notes libres du prof (contexte, origine, remarques...)
            $table->string('notes')->nullable()->after('time');
        });
    }

    public function down(): void
    {
        Schema::table('private_exercises', function (Blueprint $table) {
            $table->dropForeign(['classe_id']);
            $table->dropForeign(['chapter_id']);
            $table->dropForeign(['subchapter_id']);
            $table->dropColumn(['classe_id', 'chapter_id', 'subchapter_id', 'notes']);
        });
    }
};
