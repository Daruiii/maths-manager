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
            $table->string('title')->nullable();
            $table->enum('status', ['not_started', 'opened'])->default('not_started');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises_sheet', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('status');
        });
    }
};
