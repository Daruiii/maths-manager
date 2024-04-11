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
        Schema::create('ds_exercises_ds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ds_id')->constrained('DS')->onDelete('cascade');
            $table->foreignId('ds_exercise_id')->constrained('ds_exercises')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ds_exercises_ds');
    }
};
