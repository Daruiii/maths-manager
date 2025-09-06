<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Classe;
use App\Models\Chapter;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ajouter display_order aux classes
        if (!Schema::hasColumn('classes', 'display_order')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->integer('display_order')->after('hidden')->default(1);
            });
        }

        // 2. Initialiser les display_order des classes (ordre actuel)
        $classes = Classe::orderBy('id')->get();
        foreach ($classes as $index => $classe) {
            $classe->display_order = $index + 1;
            $classe->save();
        }

        // 3. Convertir l'ordre GLOBAL des chapitres en ordre LOCAL par classe
        $classes = Classe::orderBy('display_order')->get();
        
        foreach ($classes as $classe) {
            $chapters = Chapter::where('class_id', $classe->id)->orderBy('order')->get();
            
            foreach ($chapters as $index => $chapter) {
                // Convertir en ordre local : position dans la classe (1, 2, 3...)
                // On garde l'ancien order pour compatibilité, mais on l'update avec l'ordre local
                $chapter->order = $index + 1;
                $chapter->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('classes', 'display_order')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->dropColumn('display_order');
            });
        }

        // Restaurer l'ordre global des chapitres (complexe, on garde simple)
        // En cas de rollback, il faudra recalculer manuellement avec classe.reorder
    }
};
