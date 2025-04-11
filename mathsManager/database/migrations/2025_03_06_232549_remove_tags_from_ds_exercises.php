<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ds_exercises', function (Blueprint $table) {
            $table->dropColumn('tags'); // Suppression du champ tags
        });
    }

    public function down(): void
    {
        Schema::table('ds_exercises', function (Blueprint $table) {
            $table->json('tags')->nullable(); // Remettre tags si besoin d'annuler
        });
    }
};

