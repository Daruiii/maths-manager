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
        Schema::table('ds_exercises', function (Blueprint $table) {
            $table->string('header')->nullable()->change();
            $table->integer('time')->default(30)->change();
            $table->string('name')->nullable()->change();
            $table->text('latex_statement')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ds_exercises', function (Blueprint $table) {
            $table->string('header')->nullable(false)->change();
            $table->integer('time')->default(0)->change();
            $table->string('name')->nullable(false)->change();
            $table->text('latex_statement')->nullable(false)->change();
        });
    }
};
