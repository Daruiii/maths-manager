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
            $table->text('demonstration')->nullable()->after('latex_example');
            $table->text('latex_demonstration')->nullable()->after('demonstration');
            $table->text('remarque')->nullable()->after('latex_demonstration');
            $table->text('latex_remarque')->nullable()->after('remarque');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recap_part_blocks', function (Blueprint $table) {
            $table->dropColumn(['demonstration', 'latex_demonstration', 'remarque', 'latex_remarque']);
        });
    }
};
