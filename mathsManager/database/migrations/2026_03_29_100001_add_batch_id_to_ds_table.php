<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ds', function (Blueprint $table) {
            $table->foreignId('batch_id')
                ->nullable()
                ->after('teacher_id')
                ->constrained('ds_batches')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ds', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\DsBatch::class);
            $table->dropColumn('batch_id');
        });
    }
};
