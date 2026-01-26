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
        Schema::table('recap_parts', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('description');
        });

        Schema::table('recap_part_blocks', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('latex_remarque');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recap_parts', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('recap_part_blocks', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
