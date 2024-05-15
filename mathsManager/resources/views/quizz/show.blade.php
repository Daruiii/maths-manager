@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        {{-- bouton a gauche pour retour au index des dsexercises --}}
        <div class="flex row items-center w-full ms-12 mt-5">
            <x-back-btn path="{{ route('quizz.index') }}" />
            @if ($previousQuestion)
                <a href="{{ route('quizz.show', ['id' => $previousQuestion->id, 'filter' => $filter]) }}"
                    class="previous-btn">
                    <span>Question précédente</span>
                    <svg width="16px" height="16px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#000000">
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
            @if ($nextQuestion)
                <a href="{{ route('quizz.show', ['id' => $nextQuestion->id, 'filter' => $filter]) }}" class="next-btn">
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
                    <span>Question suivante</span>
                </a>
            @endif
        </div>
        <div class="p-4 my-5 bg-white rounded-lg box-shadow shadow-xl w-2/3 mx-auto">
            <div class="flex row justify-end items-center h-2">
                @auth
                    @if (Auth::user()->role === 'admin')
                        <div class="flex items-center space-x-2">
                            <x-button-edit href="{{ route('quizz.edit', ['id' => $question->id, 'filter' => $filter]) }}" />
                            <x-button-delete href="{{ route('quizz.destroy', $question->id) }}" entity="cet question"
                                entityId="quizz{{ $question->id }}" />
                        </div>
                    @endif
                @endauth
            </div>
            <div class="exercise-content text-sm px-4 cmu-serif text-center">
                <h1 class="font-bold text-lg mb-4">{{ $question->chapter->title }}</h1> 
                <h2 class="truncate font-bold text-sm exercise-title mb-4">Sous-chapitre: {{ $question->subchapter->title }}</h2>
                <h2 class="truncate font-bold text-sm exercise-title mb-4">Question: </h2>
                <p class="w-full break-words">{!! $question->question !!}</p>
                <h2 class="truncate font-bold text-sm exercise-title mb-4">Explication: </h2>
                <p class="w-full break-words">{!! $question->explanation !!}</p>
            </div>
        </div>
    </div>
@endsection
