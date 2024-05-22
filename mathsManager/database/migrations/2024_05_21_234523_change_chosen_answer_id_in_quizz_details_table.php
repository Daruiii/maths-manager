<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('quizz_details', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['chosen_answer_id']);
    
            // Change column to accept NULL values
            $table->unsignedBigInteger('chosen_answer_id')->nullable()->change();
    
            // Add foreign key constraint back
            $table->foreign('chosen_answer_id')->references('id')->on('quizz_answers');
        });
    }
    
    public function down()
    {
        Schema::table('quizz_details', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['chosen_answer_id']);
    
            // Change column to not accept NULL values
            $table->unsignedBigInteger('chosen_answer_id')->change();
    
            // Add foreign key constraint back
            $table->foreign('chosen_answer_id')->references('id')->on('quizz_answers');
        });
    }
};
