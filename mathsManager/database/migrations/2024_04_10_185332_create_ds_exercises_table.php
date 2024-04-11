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
        Schema::create('ds_exercises', function (Blueprint $table) {
            $table->id();
            $table->string('header')->default('latexmimigl');
            $table->foreignId('chapters_id')->constrained('chapters');
            $table->foreignId('multiple_chapter_id')->constrained('multiple_chapters');
            $table->boolean('harder_exercise')->default(false);
            $table->integer('time')->nullable();
            $table->string('name');
            $table->text('statement');
            $table->text('latex_statement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ds_exercises');
    }
};
