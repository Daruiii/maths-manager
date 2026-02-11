@extends('layouts.app')

@section('title', ($question->chapter->title ?? 'Quizz') . ' - Maths Manager')
@section('meta_description', 'Question de quizz sur le chapitre : ' . ($question->chapter->title ?? 'N/A') . '. Testez vos connaissances en maths !')
@section('canonical', url()->current())

@section('content')
    <div class="container mx-auto">
        {{-- bouton a gauche pour retour au index des dsexercises --}}
        <div class="flex row items-center w-full ms-12 mt-5">
            @if ($filter === 'true')
                <x-back-btn path="{{ route('quizz.index', ['chapter_id' => $question->chapter->id ?? null]) }}" > Retour</x-back-btn>
            @else
                <x-back-btn path="{{ route('quizz.index') }}" > Retour</x-back-btn>
            @endif
            @if ($previousQuestion)
                <a href="{{ route('quizz.show', ['id' => $previousQuestion->id, 'filter' => $filter]) }}"
                    class="previous-btn">
                    <span>Question précédente</span>
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
                            <x-button-delete href="{{ route('quizz.destroy', ['id' => $question->id, 'filter' => $filter]) }}"
                                entity="cette question"
                                entityId="quizz{{ $question->id }}" />
                        </div>
                    @endif
                @endauth
            </div>
            <div class="exercise-content text-sm px-4 text-center">
                <h1 class="font-bold text-lg my-2">{{ $question->chapter->title }}</h1>
                <h2 class="truncate font-bold text-sm exercise-title my-2">Sous-chapitre: {{ $question->subchapter->title }}
                </h2>
                <div class="flex flex-col items-center">
                    <h2 class="truncate font-bold text-sm exercise-title my-2">Question: </h2>
                    <p class="cmu-serif w-full break-words">{!! $question->question !!}</p>
                </div>

                <div class="w-full flex justify-between items-center">
                    <h2 class="truncate font-bold text-sm exercise-title my-2">Réponses: </h2>
                    <x-button-add href="{{ route('quizz.answer.create', ['id' => $question->id, 'filter' => $filter]) }}"   >
                        Réponse
                    </x-button-add>
                </div>
                @if ($question->answers->count() > 0)
                    <ul class=" list-inside">
                        @foreach ($question->answers as $answer)
                            @if ($answer->is_correct)
                                <div class="flex justify-end items-center">
                                    <x-button-edit href="{{ route('quizz.answer.edit', ['id' => $answer->id, 'filter' => $filter]) }}" />
                                    <x-button-delete href="{{ route('quizz.answer.destroy', ['id' => $answer->id, 'filter' => $filter]) }}"
                                        entity="cette réponse" entityId="quizz{{ $question->id }}{{ $answer->id }}" />
                                </div>
                                <div class="p-2 w-full break-words mb-2 border border-1 border-green-300 rounded-lg ">
                                    <li class="cmu-serif clue-content w-full break-words border border-1 rounded-lg mb-2 bg-green-100">
                                        {!! $answer->answer !!}
                                    </li>
                                    @if ($answer->explanation)
                                        <p class="font-bold text-xs">Explication:</p>
                                        <p class="clue-content cmu-serif">{!! $answer->explanation !!}</p>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                        @foreach ($question->answers as $answer)
                            @if (!$answer->is_correct)
                            <div class="flex justify-end items-center">
                                <x-button-edit href="{{ route('quizz.answer.edit', ['id' => $answer->id, 'filter' => $filter]) }}" />
                                <x-button-delete href="{{ route('quizz.answer.destroy', $answer->id) }}" entity="cette réponse" entityId="quizz{{ $question->id }}{{ $answer->id }}" />
                            </div>
                                <li class="cmu-serif clue-content w-full break-words border border-1 bg-red-100 rounded-lg mb-2">
                                    {!! $answer->answer !!} </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
