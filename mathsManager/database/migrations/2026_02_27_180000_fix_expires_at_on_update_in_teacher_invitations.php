<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix: Remove ON UPDATE CURRENT_TIMESTAMP from expires_at column.
     * MySQL adds this by default on the first TIMESTAMP column without explicit default.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE teacher_invitations MODIFY expires_at DATETIME NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert - the original behavior was a bug
    }
};
