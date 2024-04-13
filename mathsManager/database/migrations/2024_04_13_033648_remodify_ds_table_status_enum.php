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
        Schema::table('DS', function (Blueprint $table) {
            // Ajoutez la nouvelle colonne status avec les nouvelles valeurs enum
            $table->enum('status', ['not_started', 'ongoing', 'finished', 'sent', 'corrected'])->after('chrono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DS', function (Blueprint $table) {
            // Supprimez la colonne status si nécessaire lors de la migration inverse
            $table->dropColumn('status');
        });
    }
};
