<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename DS table to lowercase (Laravel convention)
     */
    public function up(): void
    {
        Schema::rename('DS', 'ds');
    }

    public function down(): void
    {
        Schema::rename('ds', 'DS');
    }
};
