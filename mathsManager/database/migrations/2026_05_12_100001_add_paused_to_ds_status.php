<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE ds MODIFY COLUMN status ENUM('not_started','ongoing','paused','finished','finished_late','sent','corrected') NOT NULL DEFAULT 'not_started'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ds MODIFY COLUMN status ENUM('not_started','ongoing','finished','finished_late','sent','corrected') NOT NULL DEFAULT 'not_started'");
    }
};
