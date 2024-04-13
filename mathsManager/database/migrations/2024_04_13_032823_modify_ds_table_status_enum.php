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
            // Supprimez la colonne status existante
            Schema::table('DS', function (Blueprint $table) {
                $table->dropColumn('status');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
     // Ajoutez la colonne status avec les nouvelles valeurs
     Schema::table('DS', function (Blueprint $table) {
        $table->enum('status', ['not_started', 'ongoing', 'finished', 'sent', 'corrected'])->after('chrono');
    });
    }
};
