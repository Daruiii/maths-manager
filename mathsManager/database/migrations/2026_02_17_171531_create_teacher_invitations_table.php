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
        Schema::create('teacher_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('code', 12)->unique();
            $table->dateTime('expires_at');
            $table->unsignedInteger('max_uses')->default(5);
            $table->unsignedInteger('current_uses')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index for fast lookup when student joins via link
            $table->index(['code', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_invitations');
    }
};
