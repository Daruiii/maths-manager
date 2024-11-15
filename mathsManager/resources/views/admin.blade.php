@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-xl font-bold mb-4">Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Bouton pour les utilisateurs -->
            <a href="{{ route('users.index') }}"
                class="p-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les utilisateurs
            </a>

            <!-- Bouton pour les classes -->
            <a href="{{ route('classe.index') }}"
                class="p-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les classes
            </a>

            <!-- Bouton pour les exercices -->
            <a href="{{ route('exercises.index') }}"
                class="p-4 bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les exercices
            </a>

            {{-- bouton pour les multiple_chapters --}}
            <a href="{{ route('multiple_chapters.index') }}"
                class="p-4 bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les chapitres duo
            </a>

            <!-- Bouton pour les ds_exercices -->
            <a href="{{ route('ds_exercises.index') }}"
                class="p-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les exercices de DS
            </a>

            <!-- Bouton pour les DS -->
            <a href="{{ route('ds.index') }}"
                class="p-4 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les DS
            </a>

            <!-- bouton pour les corrections -->
            <a href="{{ route('correctionRequest.index') }}"
                class="p-4 bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les corrections
            </a>

            <!-- button pour les quizz -->
            <a href="{{ route('quizz.index') }}"
                class="p-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les quizz
            </a>

            <!-- button pour les fiches d'exercices -->
            <a href="{{ route('exercises_sheet.index') }}"
                class="p-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les fiches d'exercices
            </a>

            <!-- Bouton pour gérer les contenus -->
            <a href="{{ route('contents.index') }}"
                class="p-4 bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les contenus
            </a>
            <!-- Ajoutez plus de boutons pour d'autres entités si nécessaire -->
        </div>
    </div>
@endsection
