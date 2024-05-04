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
        Schema::table('recap_part_blocks', function (Blueprint $table) {
            $table->text('example')->nullable()->after('content');
            $table->text('latex_example')->nullable()->after('example');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recap_part_blocks', function (Blueprint $table) {
            $table->dropColumn('example');
            $table->dropColumn('latex_example');
        });
    }
};
