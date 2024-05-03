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
        Schema::create('recap_part_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recap_part_id')->constrained('recap_parts')->onDelete('cascade');
            $table->string('title');
            $table->string('theme');
            $table->text('content');
            $table->text('latex_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recap_part_blocks');
    }
};
