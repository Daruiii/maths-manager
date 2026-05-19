@extends('layouts.app')

@section('title', "Mes Fiches d'Exercices - Maths Manager")
@section('meta_description', "Consultez et gérez vos fiches d'exercices attribuées par vos professeurs sur Maths Manager.")
@section('canonical', url()->current())

@section('content')
    <div class="container mx-auto mb-8">
        <div class="flex justify-start flex-col items-start w-9/12 mt-6 mb-4">
            <h1 class="text-xl">Mes Fiches d'Exercices</h1>
        </div>

        {{-- Affichage des messages d'erreur --}}
        @if (session('error'))
            <div class="flex justify-center items-center w-8/12 h-10 bg-red-100 rounded-lg p-2 my-3">
                <h2 class="text-red-600">{{ session('error') }}</h2>
            </div>
        @endif

        {{-- Affichage des fiches d'exercices --}}
        <div class="flex row justify-center align-center flex-wrap gap-5 w-8/12 mb-5">
            @foreach ($exercisesSheetList as $exerciseSheet)
                <a href="{{ route('exercises_sheet.show', $exerciseSheet->id) }}">
                    <x-sheet-card 
                        number="{{ $loop->iteration }}"
                        title="{{  $exerciseSheet->title ?? $exerciseSheet->chapter->title }}"
                        date="{{ $exerciseSheet->created_at->format('d/m/Y') }}" 
                        status="{{ $exerciseSheet->status }}"
                    />
                </a>
            @endforeach

            {{-- Message si aucune fiche d'exercice n'est disponible --}}
            @if ($exercisesSheetList->isEmpty())
                <div class="flex justify-center flex-col items-center w-full h-20">
                    <h2 class="text-gray-500">Vous n'avez pas encore de fiche d'exercice</h2>
                    <p class="text-center text-gray-500 text-xs">
                        Attendez qu'un professeur vous en attribue une pour commencer à travailler.
                    </p>
                </div>
            @endif
        </div>

        {{-- Pagination --}}
        {{ $exercisesSheetList->links('vendor.pagination.simple-tailwind') }}

        {{-- Bouton retour en haut de page --}}
        <x-button-back-top />
    </div>
@endsection
