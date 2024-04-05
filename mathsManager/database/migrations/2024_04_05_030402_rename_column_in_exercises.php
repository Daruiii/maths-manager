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
        Schema::table('exercises', function (Blueprint $table) {
            $table->renameColumn('latex-statement', 'latex_statement');
            $table->renameColumn('latex-solution', 'latex_solution');
            $table->renameColumn('latex-clue', 'latex_clue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->renameColumn('latex_statement', 'latex-statement');
            $table->renameColumn('latex_solution', 'latex-solution');
            $table->renameColumn('latex_clue', 'latex-clue');
        });
    }
};
