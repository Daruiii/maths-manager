<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove redundant columns (replaced by status enum)
            $table->dropColumn(['verified', 'last_ds_generated_at']);

            // Add teacher profile fields (nullable - only filled for teachers)
            $table->string('phone')->nullable()->after('status');
            $table->string('location')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('location');
            $table->enum('teaching_level', ['college', 'lycee', 'prepa', 'superieur', 'autre'])->nullable()->after('bio');
            $table->enum('diploma', ['licence', 'master', 'agregation', 'capes', 'doctorat', 'autre'])->nullable()->after('teaching_level');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('verified')->default(false);
            $table->timestamp('last_ds_generated_at')->nullable();

            $table->dropColumn(['phone', 'location', 'bio', 'teaching_level', 'diploma']);
        });
    }
};
