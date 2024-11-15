<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Content;

class ContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Content::updateOrCreate(['section' => 'home_guest_intro'], [
            'title' => 'Bienvenue sur Maths Manager',
            'content' => 'Vous trouverez sur ce site des exercices, des quizz, des fiches récapitulatives de cours
            sur tous les chapitres des classes de Première et Terminale. Les exercices ne disposant pas de correction,
            vous aurez la possibilité d’envoyer votre travail afin d’obtenir une correction de ma part. Les quizz sont
            interactifs et permettent de vérifier que le cours est su. Vous pourrez suivre votre progression durant
            l’année grâce au système de notation et de suivi des exercices et quizz. Un générateur de DS vous permet de
            concevoir de manière automatique et aléatoire un contrôle personnalisé en fonction de la difficulté, du
            temps et des chapitres sélectionnés.',
            'image' => '',
        ]);
    
        Content::updateOrCreate(['section' => 'home_guest_whoami'], [
            'title' => 'Qui suis-je ?',
            'content' => 'Après deux années de classes préparatoires MPSI, MP* j’ai intégré l’école d’ingénieur
            ENSEEIHT. Je suis professeur particulier depuis maintenant 8 années, où j’ai pu aider de nombreux élèves à
            obtenir leur Baccalauréat et poursuivre leurs études dans le supérieur.',
            'image' => '',
        ]);
    }
}
