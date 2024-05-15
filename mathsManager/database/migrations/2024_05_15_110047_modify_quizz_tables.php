<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizz_questions', function (Blueprint $table) {
            $table->dropColumn('explanation');
            $table->dropColumn('latex_explanation');
        });

        Schema::table('quizz_answers', function (Blueprint $table) {
            $table->text('explanation')->nullable();
            $table->text('latex_explanation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizz_questions', function (Blueprint $table) {
            $table->text('explanation')->nullable();
            $table->text('latex_explanation')->nullable();
        });

        Schema::table('quizz_answers', function (Blueprint $table) {
            $table->dropColumn('explanation');
            $table->dropColumn('latex_explanation');
        });
    }
};
