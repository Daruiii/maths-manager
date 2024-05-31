<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Subchapter;
use App\Models\Chapter;
use App\Models\Classe;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('exercises', 'order')) {
            Schema::table('exercises', function (Blueprint $table) {
                $table->integer('order')->default(0);
            });
        }
    
        $classes = Classe::orderBy('id')->get();
        $order = 1;
    
        foreach ($classes as $class) {
            $chapters = $class->chapters()->orderBy('id')->get();
    
            foreach ($chapters as $chapter) {
                $subchapters = $chapter->subchapters()->orderBy('id')->get();
    
                foreach ($subchapters as $subchapter) {
                    $exercises = $subchapter->exercises()->orderBy('id')->get();
    
                    foreach ($exercises as $exercise) {
                        $exercise->order = $order++;
                        $exercise->save();
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('exercises', 'order')) {
            Schema::table('exercises', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
};
