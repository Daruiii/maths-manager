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
        Schema::table('correction_requests', function (Blueprint $table) {
            $table->text('correction_pdf')->nullable()->after('correction_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('correction_requests', function (Blueprint $table) {
            $table->dropColumn('correction_pdf');
        });
    }
};
