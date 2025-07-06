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
                <div class="mt-6">
                    <h2 class="text-lg font-semibold">Solution :</h2>
                    <div class="mt-2 p-4 bg-green-50 rounded border border-green-200 solution-content cmu-serif">
                        {!! $exercise->solution !!}
                    </div>
                </div>
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
