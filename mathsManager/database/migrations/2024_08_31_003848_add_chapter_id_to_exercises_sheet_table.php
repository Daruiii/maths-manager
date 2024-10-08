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
        Schema::table('exercises_sheet', function (Blueprint $table) {
            if (!Schema::hasColumn('exercises_sheet', 'chapter_id')) {
                $table->foreignId('chapter_id')->constrained('chapters')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises_sheet', function (Blueprint $table) {
            if (Schema::hasColumn('exercises_sheet', 'chapter_id')) {
                $table->dropForeign(['chapter_id']);
                $table->dropColumn('chapter_id');
            }
        });
    }
};