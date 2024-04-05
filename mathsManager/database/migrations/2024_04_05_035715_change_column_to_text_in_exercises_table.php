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
            $table->text('latex_statement')->nullable()->change();
            $table->text('latex_solution')->nullable()->change();
            $table->text('latex_clue')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->string('latex_statement')->nullable()->change();
            $table->string('latex_solution')->nullable()->change();
            $table->string('latex_clue')->nullable()->change();
        });
    }
};
