@extends('layouts.app')

@section('title', $classe->name . ' - Maths Manager')
@section('meta_description', 'Chapitres, exercices et ressources pour la classe de ' . $classe->name . ' sur Maths Manager.')
@section('canonical', url()->current())

@section('content')
    <div class="container mx-auto chapters-container">
        <div class="flex header">
            <div>
                <h1 class="title">{{ $classe->name }}</h1>
                <h2 class="subtitle">Chapitres</h2>
            </div>
            @auth
                @if (Auth::user()->role === 'admin')
                    <div class="flex items-center space-x-2">
                        <x-button-add href="{{ route('chapter.create', $classe->id) }}">
                            {{ __('Chapitre') }}
                        </x-button-add>
                        <button id="reorder-chapters-button"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 focus:outline-none">
                            Réorganiser les chapitres
                        </button>
                    </div>
                    {{-- <a href="{{ route('exercises.decrement') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        décrement les exercices.order
                    </a> --}}
                    {{-- <a href="{{ route('classe.reorder') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        reorder chapters et subchapters
                    </a> --}}
                @endif
            @endauth
        </div>
        <div class="chapter-list" id="chapters-container">
            @foreach ($chapters as $indexChap => $chapter)
                @props(['color' => $chapter->theme])
                <div class="chapter bg-white rounded-lg p-2 mb-4" x-data="{ open: false }" 
                    id="chapter-{{ $chapter->id }}" data-order="{{ $chapter->order }}"
                    style="border-left: 5px solid {{ $chapter->theme }}; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
                    {{-- Chapitre Titre et Boutons d'Action pour Admin --}}
                    <div class="flex justify-between items-center" @click="open = !open">
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <div class="drag-handle-chapter hidden mr-2 cursor-move">☰</div>
                            @endif
                        @endauth
                        <button
                            class="flex items-center justify-between w-2/3 text-left chapter-title text-lg font-semibold text-gray-700">
                            <span class="truncate">{{ $indexChap + 1 }}. {{ $chapter->title }}</span>
                        </button>
                        <button
                            class="flex items-center justify-end w-full text-left chapter-title text-lg font-semibold text-gray-700">
                            {{-- Icône de Chevron --}}
                            <svg :class="{ 'transform rotate-180 transition-transform duration-300': open, 'transform rotate-0 transition-transform duration-300': !open }" class="w-6 h-6"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <div class="flex items-center space-x-2">
                                    <x-button-edit href="{{ route('chapter.edit', $chapter->id) }}" />
                                    <x-button-delete href="{{ route('chapter.destroy', $chapter->id) }}" entity="ce chapitre"
                                        entityId="chapitre{{ $chapter->id }}" />
                                </div>
                            @endif
                        @endauth
                    </div>
                    <div x-show="open" x-cloak x-transition:enter="transition-all ease-out duration-300"
                        x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen"
                        x-transition:leave="transition-all ease-in duration-500"
                        x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0"
                        class="overflow-hidden px-4 pt-2 pb-4">
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
                                            <x-button-delete href="{{ route('recap.destroy', $recap->id) }}" entity="ce récap"
                                                entityId="recap{{ $recap->id }}" />
                                        @endif
                                    @endauth
                                @endforeach
                                @if ($chapter->quizzQuestions->count() >= 10)
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
                                            <button id="reorder-subchapters-{{ $chapter->id }}-button"
                                                class="px-3 py-1 bg-purple-500 text-white text-xs rounded hover:bg-purple-700 focus:outline-none"
                                                data-original-text="⭯ Réorganiser">
                                                ⭯ Réorganiser
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                                <div class="space-y-2" id="subchapters-container-{{ $chapter->id }}">
                                    @foreach ($chapter->subchapters()->orderBy('order')->get() as $index => $subchapter)
                                        <div class="flex items-center justify-between px-2 border-b border-gray-200 subchapter" 
                                            id="subchapter-{{ $subchapter->id }}" data-order="{{ $subchapter->order }}">
                                            @auth
                                                @if (Auth::user()->role === 'admin')
                                                    <div class="drag-handle-subchapter hidden mr-2 cursor-move">☰</div>
                                                @endif
                                            @endauth
                                            <a href="{{ route('subchapter.show', $subchapter->id) }}"
                                                class="my-2 flex w-full items-center justify-between space-x-2 truncate hover:underline border-l-2 border-black pl-2">
                                                <span class="text-sm">{{ $indexChap + 1 }}.{{ $subchapter->order }} -
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
                                                    <div class="flex items-center space-x-2">
                                                        <x-button-edit href="{{ route('subchapter.edit', $subchapter->id) }}" />
                                                        <x-button-delete href="{{ route('subchapter.destroy', $subchapter->id) }}"
                                                            entity="ce sous-chapitre" entityId="subchapter{{ $subchapter->id }}" />
                                                    </div>
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
        <x-button-back-top />
    </div>

    {{-- JavaScript pour drag-and-drop multi-niveaux --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="{{ asset('js/multi-level-drag-drop.js') }}"></script>
    <script>
        // Configuration des IDs pour le contexte
        window.currentClassId = {{ $classe->id }};
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Drag & Drop multi-niveaux initialisé pour la classe', window.currentClassId);
            
            // Configuration pour les chapitres
            window.MultiLevelDragDrop.initLevel({
                containerId: 'chapters-container',
                handleClass: 'drag-handle-chapter',
                buttonId: 'reorder-chapters-button',
                itemClass: 'chapter',
                reorderRoute: '{{ route('ordering.reorderChapters') }}',
                level: 'chapter',
                previewRoute: '{{ route('ordering.previewMove') }}'
            });
            
            // Configuration pour les sous-chapitres avec cross-container (un bouton par chapitre)
            @foreach ($chapters as $chapter)
                window.MultiLevelDragDrop.initLevel({
                    containerId: 'subchapters-container-{{ $chapter->id }}',
                    handleClass: 'drag-handle-subchapter',
                    buttonId: 'reorder-subchapters-{{ $chapter->id }}-button',
                    itemClass: 'subchapter',
                    reorderRoute: '{{ route('ordering.reorderSubchapters') }}',
                    level: 'subchapter',
                    crossContainer: true,
                    moveRoute: '{{ route('ordering.moveSubchapter') }}'
                });
            @endforeach
            
            console.log('✅ Configuration de tous les niveaux terminée');
        });
    </script>
@endsection
