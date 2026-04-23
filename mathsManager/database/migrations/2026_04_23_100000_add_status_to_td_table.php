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
