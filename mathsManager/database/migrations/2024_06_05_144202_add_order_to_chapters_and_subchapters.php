<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Classe;
use App\Models\Chapter;
use App\Models\Subchapter;

class AddOrderToChaptersAndSubchapters extends Migration
{
    public function up()
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->integer('order')->after('id');
        });
    
        Schema::table('subchapters', function (Blueprint $table) {
            $table->integer('order')->after('id');
        });
    
        // Get all classes ordered by id
        $classes = Classe::orderBy('id')->get();
    
        $order = 1;
    
        foreach ($classes as $class) {
            // Get all chapters of the class ordered by id
            $chapters = Chapter::where('class_id', $class->id)->orderBy('id')->get();
    
            foreach ($chapters as $chapter) {
                // Assign the order to the chapter
                $chapter->order = $order++;
                $chapter->save();
    
                // Get all subchapters of the chapter ordered by id
                $subchapters = Subchapter::where('chapter_id', $chapter->id)->orderBy('id')->get();
    
                foreach ($subchapters as $index => $subchapter) {
                    // Assign the order to the subchapter
                    $subchapter->order = $index + 1;
                    $subchapter->save();
                }
            }
        }
    }

    public function down()
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('subchapters', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
?>