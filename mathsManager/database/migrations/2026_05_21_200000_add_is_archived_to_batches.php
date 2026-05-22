<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['ds_batches', 'dm_batches', 'td_batches'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->boolean('is_archived')->default(false)->after('due_date');
            });
        }
    }

    public function down(): void
    {
        foreach (['ds_batches', 'dm_batches', 'td_batches'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('is_archived');
            });
        }
    }
};
