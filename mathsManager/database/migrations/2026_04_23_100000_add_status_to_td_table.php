<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La colonne status existe déjà sur td (legacy : 'not_started', 'opened').
     * On la remplace par les valeurs du nouveau flow TdStatus.
     */
    public function up(): void
    {
        if (Schema::hasColumn('td', 'status')) {
            // Étape 1 : élargir l'ENUM pour accepter les deux anciennes et nouvelles valeurs
            DB::statement("ALTER TABLE td MODIFY COLUMN status ENUM('not_started','opened','ongoing','correction_requested','correction_unlocked') NOT NULL DEFAULT 'not_started'");
            // Étape 2 : migrer opened → ongoing
            DB::statement("UPDATE td SET status = 'ongoing' WHERE status = 'opened'");
            // Étape 3 : restreindre l'ENUM aux nouvelles valeurs uniquement
            DB::statement("ALTER TABLE td MODIFY COLUMN status ENUM('not_started','ongoing','correction_requested','correction_unlocked') NOT NULL DEFAULT 'not_started'");
        } else {
            Schema::table('td', function (Blueprint $table) {
                $table->enum('status', ['not_started', 'ongoing', 'correction_requested', 'correction_unlocked'])
                    ->default('not_started')
                    ->after('batch_id');
            });
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE td MODIFY COLUMN status ENUM('not_started','opened') NOT NULL DEFAULT 'not_started'");
    }
};
