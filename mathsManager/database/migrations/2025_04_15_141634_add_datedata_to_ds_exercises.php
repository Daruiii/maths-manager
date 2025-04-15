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
            // add a date data to the ds_exercises table, in string format, could looks like "mai 2022 s2"
            $table->string('date_data')->nullable()->after('latex_statement'); // date data in string format
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ds_exercises', function (Blueprint $table) {
            $table->dropColumn('date_data'); // remove the date data
        });
    }
};
