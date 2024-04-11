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
        Schema::create('DS', function (Blueprint $table) {
            $table->id();
            $table->boolean('type_bac')->default(false);
            $table->integer('exercises_number');
            $table->boolean('harder_exercises')->default(false);
            // time = la somme des temps de chaque exercice et donc le temps total du DS
            $table->integer('time');
            // timer sera time au debut et sera decremente a chaque fois qu'un eleve commence un DS, 
            // comme ca un eleve pourra mettre en pause et reprendre le DS
            // si l'élève clique sur terminé puis envoyer, ça enverra le ds avec finished et le time - timer
            $table->integer('timer');
            // se lance lorsque le timer arrive a 0, l'élève pourra mettre en pause et reprendre le DS il sera écrit +{chrono}
            // si l'élève a depassé le temps et le bouton terminé renverra la ds avec finishedLate et le time +{chrono}
            $table->integer('chrono');
            $table->enum('status', ['break', 'ongoing', 'finished', 'finishedLate']);
            $table->timestamps();
        });

        Schema::create('ds_chapter', function (Blueprint $table) {
            $table->foreignId('ds_id')->constrained('DS')->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained('chapters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ds_chapter');
        Schema::dropIfExists('DS');
    }
};