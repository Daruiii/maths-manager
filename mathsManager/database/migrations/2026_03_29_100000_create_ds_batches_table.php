<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ds_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            /** IDs des groupes intentionnellement ciblés (peut différer des student_ids si décoches individuelles) */
            $table->json('group_ids')->nullable();
            /** IDs des élèves effectivement destinataires après sélection finale */
            $table->json('student_ids');
            $table->unsignedInteger('ds_count');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ds_batches');
    }
};
