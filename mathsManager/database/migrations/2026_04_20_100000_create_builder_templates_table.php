<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('builder_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // 'ds' | 'td' | 'dm'
            $table->string('name');
            $table->foreignId('student_group_id')->nullable()->constrained('student_groups')->nullOnDelete();
            $table->json('payload'); // { items: DSPreviewItem[], title?, level?, instructions? }
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('builder_templates');
    }
};
