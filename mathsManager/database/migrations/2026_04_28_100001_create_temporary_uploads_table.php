<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temporary_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')
                ->constrained('temporary_upload_sessions')
                ->cascadeOnDelete();
            $table->string('path');
            $table->string('original_name');
            $table->string('mime', 50);
            $table->unsignedInteger('size');
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temporary_uploads');
    }
};
