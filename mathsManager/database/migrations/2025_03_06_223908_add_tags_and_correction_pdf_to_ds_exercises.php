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
            $table->json('tags')->nullable()->after('name'); // Pour stocker plusieurs tags
            $table->string('correction_pdf')->nullable()->after('latex_statement'); // Pour le PDF
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ds_exercises', function (Blueprint $table) {
            $table->dropColumn('tags');
            $table->dropColumn('correction_pdf');
        });
    }
};