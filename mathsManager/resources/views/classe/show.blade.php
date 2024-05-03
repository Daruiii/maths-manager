@extends('layouts.app')

@section('content')
    <div class="container mx-auto chapters-container">
        <div class="flex header">
            <div>
                <h1 class="title">{{ $classe->name }}</h1>
                <h2 class="subtitle">Chapitres</h2>
            </div>
            @auth
                @if (Auth::user()->role === 'admin')
                    <x-button-add href="{{ route('chapter.create', $classe->id) }}">
                        {{ __('Chapitre') }}
                    </x-button-add>
                @endif
            @endauth
        </div>
        <div class=" chapter-list">
            @foreach ($chapters as $indexChap => $chapter)
                @props(['color' => $chapter->theme])
                <div class="chapter bg-white rounded-lg p-2 mb-4"x-data="{ open: false }"
                    style="border-left: 5px solid {{ $chapter->theme }}; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
                    {{-- Chapitre Titre et Boutons d'Action pour Admin --}}
                    <div class="flex justify-between items-center" @click="open = !open">
                        <button
                            class="flex items-center justify-between w-2/3 text-left chapter-title text-lg font-semibold text-gray-700">
                            <span class="truncate">{{ $indexChap + 1 }}. {{ $chapter->title }}</span>
                        </button>
                        <button
                            class="flex items-center justify-end w-full text-left chapter-title text-lg font-semibold text-gray-700">
                            {{-- Icône de Chevron --}}
                            <svg :class="{ 'transform rotate-180': open }" class="w-6 h-6"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <div class="flex items-center space-x-2">
                                    <x-button-edit href="{{ route('chapter.edit', $chapter->id) }}" />
                                    <form method="POST" action="{{ route('chapter.destroy', $chapter->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce chapitre ?')"
                                            class="p-2 rounded-full text-red-500 hover:text-red-600">
                                            {{-- Icône de Suppression --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                    <div x-show="open" x-cloak class="px-4 pt-2 pb-4">
                        <div class="flex flex-col items-start space-x-2 mb-4">
                            @auth
                                @if (Auth::user()->role === 'admin')
                                <div class="flex items-center space-x-3">
                                <x-button-add href="{{ route('recap.create', ['id' => $chapter->id]) }}">
                                        {{ __('Récap') }}
                                    </x-button-add>
                                    {{-- {{ route('quiz.create', ['id' => $chapter->id]) }} --}}
                                    <x-button-add href="#quiz">
                                        {{ __('Quiz') }}
                                    </x-button-add>
                                </div>
                                @endif
                            @endauth
                            <div class="flex items-center space-x-3 p-2 flex-wrap">
                            @foreach ($chapter->recaps as $index => $recap)
                                <x-button-recap href="{{ route('recap.show', $recap->id) }}">
                                    {{ __('Récap') }}
                                </x-button-recap>
                                @auth 
                                @if (Auth::user()->role === 'admin')
                                <form action="{{ route('recap.destroy', $recap->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce récap ?')"
                                        class="text-red-600 hover:text-red-900">
                                          <svg height="12px" width="12px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.001 512.001" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path style="fill:#FF757C;" d="M495.441,72.695L439.306,16.56c-8.498-8.498-22.278-8.498-30.777,0L271.389,153.7 c-8.498,8.498-22.278,8.498-30.777,0L103.472,16.56c-8.498-8.498-22.278-8.498-30.777,0L16.56,72.695 c-8.498,8.498-8.498,22.278,0,30.777l137.14,137.14c8.498,8.498,8.498,22.278,0,30.777L16.56,408.529 c-8.498,8.498-8.498,22.278,0,30.777l56.136,56.136c8.498,8.498,22.278,8.498,30.777,0l137.14-137.14 c8.498-8.498,22.278-8.498,30.777,0l137.14,137.14c8.498,8.498,22.278,8.498,30.777,0l56.136-56.136 c8.498-8.498,8.498-22.278,0-30.777l-137.14-137.139c-8.498-8.498-8.498-22.278,0-30.777l137.14-137.14 C503.941,94.974,503.941,81.194,495.441,72.695z"></path> <g> <path style="fill:#4D4D4D;" d="M88.084,511.999c-8.184,0-16.369-3.115-22.6-9.346L9.347,446.518 c-12.462-12.462-12.462-32.739,0-45.201l137.14-137.14c4.508-4.508,4.508-11.843,0-16.351L9.347,110.685 c-12.462-12.463-12.462-32.74,0-45.201L65.482,9.348c12.464-12.462,32.74-12.462,45.201,0l137.141,137.14 c4.508,4.508,11.843,4.508,16.351,0l137.14-137.14c12.461-12.461,32.738-12.462,45.2,0l56.138,56.136 c12.462,12.462,12.462,32.739,0,45.201l-137.14,137.14c-4.508,4.508-4.508,11.843,0,16.351l137.14,137.14 c12.462,12.463,12.462,32.74,0,45.201l-56.136,56.136c-12.464,12.462-32.74,12.462-45.201,0l-137.141-137.14 c-4.508-4.508-11.843-4.508-16.351,0l-137.14,137.14C104.454,508.884,96.268,511.999,88.084,511.999z M88.084,20.391 c-2.961,0-5.922,1.127-8.177,3.381L23.772,79.908c-4.508,4.508-4.508,11.844,0,16.352l137.14,137.139 c12.462,12.462,12.462,32.739,0,45.201l-137.14,137.14c-4.508,4.508-4.508,11.844,0,16.351l56.136,56.137 c4.508,4.508,11.843,4.507,16.351,0l137.14-137.14c12.463-12.463,32.739-12.463,45.201,0l137.14,137.139 c4.508,4.509,11.842,4.508,16.352,0l56.135-56.136c4.508-4.508,4.508-11.844,0-16.352L351.089,278.602 c-12.462-12.462-12.462-32.739,0-45.201l137.14-137.14c4.508-4.508,4.508-11.844,0-16.351l0,0l-56.136-56.136 c-4.509-4.507-11.844-4.507-16.351,0l-137.14,137.139c-12.463,12.463-32.739,12.463-45.201,0L96.259,23.772 C94.005,21.518,91.045,20.391,88.084,20.391z"></path> <path style="fill:#4D4D4D;" d="M88.935,473.447c-2.611,0-5.22-0.996-7.212-2.988c-3.983-3.983-3.983-10.442,0-14.426l82.476-82.475 c3.984-3.983,10.441-3.983,14.426,0c3.983,3.983,3.983,10.442,0,14.426L96.148,470.46 C94.155,472.452,91.545,473.447,88.935,473.447z"></path> <path style="fill:#4D4D4D;" d="M195.201,367.181c-2.611,0-5.22-0.996-7.212-2.987c-3.983-3.983-3.983-10.442,0-14.426l6.873-6.873 c3.984-3.983,10.44-3.983,14.426,0c3.983,3.983,3.983,10.442,0,14.426l-6.873,6.873 C200.421,366.184,197.812,367.181,195.201,367.181z"></path> </g> 
                                        </g></svg>
                                    </button>
                                </form>
                                @endif
                                @endauth
                            @endforeach
                            @foreach ($chapter->quizzes as $index => $quiz)
                                <x-button-quiz href="#quiz">
                                    {{ __('Quiz') }}
                                </x-button-quiz>
                            @endforeach
                            </div>
                            <div class="flex flex-col w-full bg-gray-100 p-4 rounded-lg">
                                <div class="flex items-center space-x-2 mb-2">
                                    <p class="comfortaa text-sm truncate">Exercices par thème :</p>
                                    @auth
                                        @if (Auth::user()->role === 'admin')
                                            <x-button-add href="{{ route('subchapter.create', ['id' => $chapter->id]) }}">
                                                {{ __('Sous-chap') }}
                                            </x-button-add>
                                        @endif
                                    @endauth
                                </div>
                                <div class="space-y-2">
                                    @foreach ($chapter->subchapters as $index => $subchapter)
                                        <div class="flex items-center justify-between px-2 border-b border-gray-200">
                                            <a href="{{ route('subchapter.show', $subchapter->id) }}"
                                                class="my-2 flex w-full items-center justify-between space-x-2 truncate hover:underline border-l-2 border-black pl-2">
                                                <span class="text-sm">{{ $indexChap + 1 }}.{{ $index + 1 }} -
                                                    {{ $subchapter->title }}</span>
                                                <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" stroke="#000000">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="#000000"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        </path>
                                                    </g>
                                                </svg>
                                            </a>
                                            @auth
                                                @if (Auth::user()->role === 'admin')
                                                    <x-button-edit href="{{ route('subchapter.edit', $subchapter->id) }}" />
                                                    <form method="POST"
                                                        action="{{ route('subchapter.destroy', $subchapter->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sous-chapitre ?')"
                                                            class="p-2 rounded-full text-red-500 hover:text-red-600">
                                                            {{-- Icône de Suppression --}}
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
