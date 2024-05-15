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
                                    <x-button-delete href="{{ route('chapter.destroy', $chapter->id) }}" entity="ce chapitre" entityId="chapitre{{$chapter->id}}" />
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
                                <x-button-delete href="{{ route('recap.destroy', $recap->id) }}" entity="ce récap" entityId="recap{{$recap->id}}" />
                                @endif
                                @endauth
                            @endforeach
                            @if ($chapter->quizzQuestions->count() > 5)
                            <x-button-quizz href="{{ route('start_quizz', $chapter->id) }}">
                                {{ __('Quizz') }}
                            </x-button-quizz>
                            @endif
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
                                                    <x-button-delete href="{{ route('subchapter.destroy', $subchapter->id) }}"
                                                        entity="ce sous-chapitre" entityId="subchapter{{$subchapter->id}}" />
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
