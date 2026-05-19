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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('calendly_invite_sent')->default(false)->after('status');
            $table->timestamp('calendly_invite_sent_at')->nullable()->after('calendly_invite_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['calendly_invite_sent', 'calendly_invite_sent_at']);
        });
    }
};
