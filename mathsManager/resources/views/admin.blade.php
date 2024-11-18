@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <x-back-btn path="{{ route('home') }}" label="retour" />

        <h1 class="text-2xl font-bold text-center mb-8">Dashboard Admin</h1>
        <!-- Gestion des bases utilisateurs et exercices -->
        <div class="flex flex-col md:flex-row gap-4 mt-6 mb-2">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <x-admin-card image="{{ asset('storage/images/Icons/users.png') }}" title="Gérer les utilisateurs"
                    href="{{ route('users.index') }}" />
                <x-admin-card image="{{ asset('storage/images/Icons/classes.png') }}" title="Gérer les classes"
                    href="{{ route('classe.index') }}" />
            </div>
            <div class="border-l-2 border-gray-300 hidden md:block"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Exercice indépendants -->
                <x-admin-card image="{{ asset('storage/images/Icons/exercises.png') }}" title="Gérer les exercices"
                    href="{{ route('exercises.index') }}" />
                <x-admin-card image="{{ asset('storage/images/Icons/sheets.png') }}" title="Gérer les fiches d'exercices"
                    href="{{ route('exercises_sheet.index') }}" />
                <!-- Quizz -->
                <x-admin-card image="{{ asset('storage/images/Icons/quizz.png') }}" title="Gérer les quizz"
                    href="{{ route('quizz.index') }}" />
            </div>
        </div>

        <!-- Devoirs surveillés -->
        <div class="flex flex-col mt-6 mb-2">
            <h2 class="text-lg font-semibold mb-2">Devoirs surveillés (DS)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-admin-card image="{{ asset('storage/images/Icons/ds-exercises.png') }}" title="Exercices de DS"
            href="{{ route('ds_exercises.index') }}" type="2" />
            <x-admin-card image="{{ asset('storage/images/Icons/ds.png') }}" title="Gérer les DS"
            href="{{ route('ds.index') }}" type="2" />
            <x-admin-card image="{{ asset('storage/images/Icons/corrections.png') }}" title="Gérer les corrections"
            href="{{ route('correctionRequest.index') }}" type="2" />
            <x-admin-card image="{{ asset('storage/images/Icons/chapters.png') }}" title="Chapitres Duo"
            href="{{ route('multiple_chapters.index') }}" type="2" />
            </div>
        </div>

        <!-- Contenus dynamiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6 mb-2">
            <!-- Bouton pour gérer les contenus -->
            <a href="{{ route('contents.index') }}"
                class="p-4 bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded text-center">
                Gérer les contenus
            </a>
        </div>
    </div>
@endsection
