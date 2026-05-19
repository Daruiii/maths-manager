<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ds', function (Blueprint $table) {
            $table->foreignId('teacher_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();
        });

        // Backfill best-effort:
        // - Si user_id est un prof/admin → c'est lui le teacher
        // - Si user_id est un élève → on prend son teacher_id dans users
        $teacherRoles = ['admin', 'teacher'];

        DB::table('ds')
            ->join('users', 'ds.user_id', '=', 'users.id')
            ->whereIn('users.role', $teacherRoles)
            ->update(['ds.teacher_id' => DB::raw('ds.user_id')]);

        DB::table('ds')
            ->join('users', 'ds.user_id', '=', 'users.id')
            ->whereNotIn('users.role', $teacherRoles)
            ->whereNotNull('users.teacher_id')
            ->update(['ds.teacher_id' => DB::raw('users.teacher_id')]);
    }

    public function down(): void
    {
        Schema::table('ds', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};
