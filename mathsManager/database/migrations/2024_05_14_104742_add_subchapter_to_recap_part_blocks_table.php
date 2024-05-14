<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recap_part_blocks', function (Blueprint $table) {
            // add subchapter_id column linked to my table subchapters and nullable
            $table->foreignId('subchapter_id')->nullable()->constrained('subchapters')->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::table('recap_part_blocks', function (Blueprint $table) {
            $table->dropColumn('subchapter_id');
        });
    }
};