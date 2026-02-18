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
            $table->enum('status', ['active', 'pending_approval', 'rejected', 'banned'])
                  ->default('active')
                  ->after('role');
            
            // Index for filtering (admin views pending teachers)
            $table->index('status');
        });

        // Assign all existing students to maxime@mathsmanager.fr
        $maximeId = DB::table('users')->where('email', 'maxime@mathsmanager.fr')->value('id');
        
        if ($maximeId) {
            DB::table('users')
                ->where('role', 'student')
                ->whereNull('teacher_id')
                ->update(['teacher_id' => $maximeId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};
