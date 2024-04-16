@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
    {{-- bouton a gauche pour retour au index des dsexercises --}}
    <div class="flex justify-start w-full ms-12 mt-5">
    <a href="{{ route('ds_exercises.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Retour</a>
    </div>
        <div class="my-5 bg-white rounded-lg box-shadow shadow-xl w-4/5">
        <div class="p-4">
            @if ($dsExercise->name)
                <div class="flex row justify-end items-center h-2">
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('ds_exercise.destroy', $dsExercise->id) }}" method="POST" class="inline">
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
                                </form>
                                <form action="{{ route('ds_exercise.edit', ['id' => $dsExercise->id]) }}" method="GET"
                                    class="inline">
                                    <button type="submit" class="p-2 rounded-full text-red-500 hover:text-red-600">
                                        <svg fill="#000000" class="h-3 w-3" version="1.1" id="Capa_1"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            viewBox="0 0 348.882 348.882" xml:space="preserve">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier">
                                                <g>
                                                    <path
                                                        d="M333.988,11.758l-0.42-0.383C325.538,4.04,315.129,0,304.258,0c-12.187,0-23.888,5.159-32.104,14.153L116.803,184.231 c-1.416,1.55-2.49,3.379-3.154,5.37l-18.267,54.762c-2.112,6.331-1.052,13.333,2.835,18.729c3.918,5.438,10.23,8.685,16.886,8.685 c0,0,0.001,0,0.001,0c2.879,0,5.693-0.592,8.362-1.76l52.89-23.138c1.923-0.841,3.648-2.076,5.063-3.626L336.771,73.176 C352.937,55.479,351.69,27.929,333.988,11.758z M130.381,234.247l10.719-32.134l0.904-0.99l20.316,18.556l-0.904,0.99 L130.381,234.247z M314.621,52.943L182.553,197.53l-20.316-18.556L294.305,34.386c2.583-2.828,6.118-4.386,9.954-4.386 c3.365,0,6.588,1.252,9.082,3.53l0.419,0.383C319.244,38.922,319.63,47.459,314.621,52.943z">
                                                    </path>
                                                    <path
                                                        d="M303.85,138.388c-8.284,0-15,6.716-15,15v127.347c0,21.034-17.113,38.147-38.147,38.147H68.904 c-21.035,0-38.147-17.113-38.147-38.147V100.413c0-21.034,17.113-38.147,38.147-38.147h131.587c8.284,0,15-6.716,15-15 s-6.716-15-15-15H68.904c-37.577,0-68.147,30.571-68.147,68.147v180.321c0,37.576,30.571,68.147,68.147,68.147h181.798 c37.576,0,68.147-30.571,68.147-68.147V153.388C318.85,145.104,312.134,138.388,303.85,138.388z">
                                                    </path>
                                                </g>
                                            </g>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="exercise-content text-sm px-4 cmu-serif">
                    {!! $dsExercise->header !!}
                    <h2 class="truncate font-bold text-sm exercise-title">Exercice from {{ $multipleChapter->title }} :
                        {{ $dsExercise->name }}</h2>
                    {!! $dsExercise->statement !!}
                </div>
            @else
                @auth
                    @if (Auth::user()->role === 'admin')
                        <div class="flex row justify-end items-center h-2">
                            <form action="{{ route('ds_exercise.destroy', $dsExercise->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-full text-red-500 hover:text-red-600"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice ?');">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                            <form action="{{ route('ds_exercise.edit', ['id' => $dsExercise->id]) }}" method="GET"
                                class="inline">
                                <button type="submit" class="p-2 rounded-full text-red-500 hover:text-red-600">
                                    <svg fill="#000000" class="h-3 w-3" version="1.1" id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 348.882 348.882" xml:space="preserve">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <g>
                                                <path
                                                    d="M333.988,11.758l-0.42-0.383C325.538,4.04,315.129,0,304.258,0c-12.187,0-23.888,5.159-32.104,14.153L116.803,184.231 c-1.416,1.55-2.49,3.379-3.154,5.37l-18.267,54.762c-2.112,6.331-1.052,13.333,2.835,18.729c3.918,5.438,10.23,8.685,16.886,8.685 c0,0,0.001,0,0.001,0c2.879,0,5.693-0.592,8.362-1.76l52.89-23.138c1.923-0.841,3.648-2.076,5.063-3.626L336.771,73.176 C352.937,55.479,351.69,27.929,333.988,11.758z M130.381,234.247l10.719-32.134l0.904-0.99l20.316,18.556l-0.904,0.99 L130.381,234.247z M314.621,52.943L182.553,197.53l-20.316-18.556L294.305,34.386c2.583-2.828,6.118-4.386,9.954-4.386 c3.365,0,6.588,1.252,9.082,3.53l0.419,0.383C319.244,38.922,319.63,47.459,314.621,52.943z">
                                                </path>
                                                <path
                                                    d="M303.85,138.388c-8.284,0-15,6.716-15,15v127.347c0,21.034-17.113,38.147-38.147,38.147H68.904 c-21.035,0-38.147-17.113-38.147-38.147V100.413c0-21.034,17.113-38.147,38.147-38.147h131.587c8.284,0,15-6.716,15-15 s-6.716-15-15-15H68.904c-37.577,0-68.147,30.571-68.147,68.147v180.321c0,37.576,30.571,68.147,68.147,68.147h181.798 c37.576,0,68.147-30.571,68.147-68.147V153.388C318.85,145.104,312.134,138.388,303.85,138.388z">
                                                </path>
                                            </g>
                                        </g>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
                <div class="exercise-content text-sm px-4 cmu-serif">
                    <div>{!! $dsExercise->header !!}</div>
                    <span class="truncate font-bold text-sm exercise-title"> Exercice from
                        {{ $multipleChapter->title }} :</span> {!! $dsExercise->statement !!}
                </div>
            @endif
        </div>
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const elements = document.querySelectorAll('.exercise-content');
            elements.forEach((element) => {
                renderMathInElement(element, {
                    delimiters: window.katexDelimiters,
                    macros: window.macros,
                    throwOnError: false,
                });
            });
        });
    </script>
@endsection
