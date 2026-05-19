<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('teacher_joined_at')
                ->nullable()
                ->after('teacher_id');
        });

        DB::table('users')
            ->whereNotNull('teacher_id')
            ->whereNull('teacher_joined_at')
            ->update([
                'teacher_joined_at' => DB::raw('COALESCE(updated_at, created_at)'),
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('teacher_joined_at');
        });
    }
};
