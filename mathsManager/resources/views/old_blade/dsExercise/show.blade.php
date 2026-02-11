@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        {{-- bouton a gauche pour retour au index des dsexercises --}}
        <div class="flex row items-center w-full ms-12 mt-5">
            @if ($filter === 'true')
                <x-back-btn
                    path="{{ route('ds_exercises.index', ['multiple_chapter_id' => $multipleChapter->id ?? null]) }}">
                    Retour</x-back-btn>
            @else
                <x-back-btn path=""> Retour</x-back-btn>
            @endif
            @if ($previousExercise)
                <a href="{{ route('ds_exercise.show', ['id' => $previousExercise->id, 'filter' => $filter]) }}"
                    class="previous-btn">
                    <span>Exercice précédent</span>
                    <svg width="16px" height="16px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
                        fill="#000000">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill="#000000"
                                d="M800 512 534.592 246.656a32 32 0 0 0-45.312 45.312L724.688 512 489.28 747.344a32 32 0 0 0 45.312 45.312L800 512z">
                            </path>
                            <path fill="#000000" d="M224 480h640a32 32 0 1 0 0-64H224a32 32 0 0 0 0 64z">
                            </path>
                        </g>
                    </svg>
                </a>
            @endif
            @if ($nextExercise)
                <a href="{{ route('ds_exercise.show', ['id' => $nextExercise->id, 'filter' => $filter]) }}"
                    class="next-btn">
                    <svg width="16px" height="16px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
                        fill="#000000">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill="#000000" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z">
                            </path>
                            <path fill="#000000"
                                d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z">
                            </path>
                        </g>
                    </svg>
                    <span>Exercice suivant</span>
                </a>
            @endif
        </div>
        <div class="p-4 my-5 bg-white rounded-lg box-shadow shadow-xl w-2/3">
            @if ($dsExercise->name)
                <div class="flex row justify-end items-center h-2">
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <div class="flex items-center space-x-2">
                                {{-- <form action="{{ route('ds_exercise.destroy', $dsExercise->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-full text-red-500 hover:text-red-600"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice ?');">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form> --}}
                                <x-button-edit
                                    href="{{ route('ds_exercise.edit', ['id' => $dsExercise->id, 'filter' => $filter]) }}" />
                                <x-button-delete href="{{ route('ds_exercise.destroy', $dsExercise->id) }}"
                                    entity="cet exercice" entityId="ds_exercise{{ $dsExercise->id }}" />
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="exercise-content text-sm px-4 cmu-serif">
                    <h2 class="truncate font-bold text-sm exercise-title">Exercice from {{ $multipleChapter->title }} :
                        {{ $dsExercise->name }} #{{ $dsExercise->id }}</h2>
                    {!! $dsExercise->statement !!}
                    @if ($dsExercise->correction_pdf)
                        <div class="mt-4">
                            <h3>Correction de l'exercice :</h3>
                            <a href="{{ asset('storage/' . $dsExercise->correction_pdf) }}" target="_blank"
                                class="btn btn-primary">
                                📄 Télécharger la correction
                            </a>
                        </div>
                    @endif
                </div>
            @else
                @auth
                    @if (Auth::user()->role === 'admin')
                        <div class="flex row justify-end items-center h-2">
                            <x-button-edit
                                href="{{ route('ds_exercise.edit', ['id' => $dsExercise->id, 'filter' => $filter]) }}" />
                            <x-button-delete href="{{ route('ds_exercise.destroy', $dsExercise->id) }}" entity="cet exercice"
                                entityId="ds_exercise{{ $dsExercise->id }}" />
                        </div>
                    @endif
                @endauth
                <div class="exercise-content text-sm px-4 cmu-serif">
                    <div>{!! $dsExercise->header !!}</div>
                    <span class="truncate font-bold text-sm exercise-title"> Exercice from
                        {{ $multipleChapter->title }} : #{{ $dsExercise->id }}</span> {!! $dsExercise->statement !!}
                </div>
            @endif
        </div>
    </div>
@endsection
