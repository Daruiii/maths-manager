<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Subchapter;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->integer('order')->default(0);
        });
    
        $subchapters = Subchapter::orderBy('id')->get();
        $order = 1;
    
        foreach ($subchapters as $subchapter) {
            $exercises = $subchapter->exercises()->orderBy('id')->get();
    
            foreach ($exercises as $exercise) {
                $exercise->order = $order++;
                $exercise->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
