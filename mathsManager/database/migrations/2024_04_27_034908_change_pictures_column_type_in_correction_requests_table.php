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
        Schema::table('correction_requests', function (Blueprint $table) {
            // I want to change pictures column type from string to text and correction_pictures column type from string to text
            $table->text('pictures')->change();
            $table->text('correction_pictures')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('correction_requests', function (Blueprint $table) {
            $table->string('pictures')->change();
            $table->string('correction_pictures')->nullable()->change();
        });
    }
};
