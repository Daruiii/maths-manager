@extends('layouts.app')

@section('title', ($exercise->name ?? 'Exercice') . ' - Maths Manager')
@section('meta_description', 'Exercice de maths : ' . ($exercise->name ?? 'Sans titre') . '. Consultez l’énoncé, la difficulté et la solution.')
@section('canonical', url()->current())

@section('content')
    <div class="container mx-auto">
        <!-- Boutons de navigation -->
        <div class="flex items-center w-full mt-5 space-x-4">
            <!-- Bouton retour -->
            <x-back-btn path="">Retour</x-back-btn>
        </div>

        <!-- Contenu de l'exercice -->
        <div class="p-6 my-5 bg-white rounded-lg shadow-md w-2/3 mx-auto">
            <!-- En-tête avec le nom de l'exercice -->
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-blue-700">
                    {{ $exercise->name ?? 'Exercice Sans Titre' }} n°{{ $exercise->order }} #{{
                        $exercise->id }}
                </h1>
                @auth
                    @if (Auth::user()->role === 'admin')
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('exercise.whitelist.show', $exercise->id) }}" 
                               class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-md shadow-sm text-sm leading-4 font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                🔒 Corrections
                            </a>
                            <x-button-edit href="{{ route('exercise.edit', $exercise->id) }}" />
                            <x-button-delete href="{{ route('exercise.destroy', $exercise->id) }}" entity="cet exercice" entityId="exercise{{ $exercise->id }}" />
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Énoncé -->
            <div class="mt-6">
                <x-stars-difficulty starsActive="{{ $exercise->difficulty }}" id="rating{{ $exercise->id }}" />
                <h2 class="text-lg font-semibold">Énoncé :</h2>
                <div class="mt-2 p-4 bg-gray-50 rounded border border-gray-200 exercise-content cmu-serif">
                    {!! $exercise->statement !!}
                </div>
            </div>

            <!-- Solution -->
            @if ($exercise->solution)
                @auth
                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'teacher' || $exercise->isWhitelisted(Auth::id()))
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold">Solution :</h2>
                            <div class="mt-2 p-4 bg-green-50 rounded border border-green-200 solution-content cmu-serif">
                                {!! $exercise->solution !!}
                            </div>
                        </div>
                    @else
                        <div class="mt-6">
                            <div class="p-4 bg-red-50 rounded border border-red-200 text-center">
                                <p class="text-red-700">🔒 <strong>Solution masquée</strong></p>
                                <p class="text-red-600 text-sm mt-2">Vous n'avez pas accès à la solution de cet exercice.</p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="mt-6">
                        <div class="p-4 bg-red-50 rounded border border-red-200 text-center">
                            <p class="text-red-700">🔒 <strong>Solution masquée</strong></p>
                            <p class="text-red-600 text-sm mt-2">Vous devez être connecté pour accéder à la solution.</p>
                        </div>
                    </div>
                @endauth
            @endif

            <!-- Indice -->
            @if ($exercise->clue)
                <div class="mt-6">
                    <h2 class="text-lg font-semibold">Indice :</h2>
                    <div class="mt-2 p-4 bg-yellow-50 rounded border border-yellow-200 clue-content cmu-serif">
                        {!! $exercise->clue !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
