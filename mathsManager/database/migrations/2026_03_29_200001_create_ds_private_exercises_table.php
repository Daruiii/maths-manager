<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ds_private_exercises', function (Blueprint $table) {
            $table->foreignId('ds_id')->constrained('ds')->cascadeOnDelete();
            $table->foreignId('private_exercise_id')->constrained('private_exercises')->cascadeOnDelete();
            $table->primary(['ds_id', 'private_exercise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ds_private_exercises');
    }
};
