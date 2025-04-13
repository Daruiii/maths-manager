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
            $table->enum('type', ['bac', 'mimigl', 'lycee', 'concours'])->nullable()->after('name');
            $table->year('year')->nullable()->after('type'); // on peut mettre un select de 1950 à 2025 côté vue
            $table->string('academy')->nullable()->after('year'); // string pour rester souple
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ds_exercises', function (Blueprint $table) {
            $table->dropColumn(['type', 'year', 'academy']);
        });
    }
};
