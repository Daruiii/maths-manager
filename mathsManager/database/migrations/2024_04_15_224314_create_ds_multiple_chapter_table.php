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
        Schema::create('ds_multiple_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ds_id')->constrained('DS')->onDelete('cascade');
            $table->foreignId('multiple_chapter_id')->constrained('multiple_chapters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ds_multiple_chapters');
    }
};
