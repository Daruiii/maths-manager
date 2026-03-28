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
        Schema::table('ds', function (Blueprint $table) {
            $table->string('custom_title')->nullable()->after('status');
            $table->string('custom_level')->nullable()->after('custom_title');
            $table->text('custom_instructions')->nullable()->after('custom_level');
        });
    }

    public function down(): void
    {
        Schema::table('ds', function (Blueprint $table) {
            $table->dropColumn(['custom_title', 'custom_level', 'custom_instructions']);
        });
    }
};
