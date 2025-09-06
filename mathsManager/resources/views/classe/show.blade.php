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
    <script>
        // Configuration pour les chapitres
        window.currentClassId = {{ $classe->id }};
        let chaptersReorderMode = false;
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Script chargé, classe ID:', window.currentClassId);
            
            const button = document.getElementById('reorder-chapters-button');
            console.log('🔘 Bouton trouvé:', button);
            
            if (button) {
                button.addEventListener('click', function() {
                    console.log('🖱️ Bouton cliqué !');
                    chaptersReorderMode = !chaptersReorderMode;
                    
                    if (chaptersReorderMode) {
                        // Activer le mode réorganisation
                        this.classList.remove('bg-blue-500', 'hover:bg-blue-700');
                        this.classList.add('bg-green-500', 'hover:bg-green-700');
                        this.textContent = 'Terminer la réorganisation';
                        
                        // Montrer les drag handles
                        document.querySelectorAll('.drag-handle-chapter').forEach(handle => {
                            handle.classList.remove('hidden');
                        });
                        
                        console.log('✅ Mode réorganisation activé');
                        
                        // Créer l'instance Sortable
                        if (typeof Sortable !== 'undefined') {
                            new Sortable(document.getElementById('chapters-container'), {
                                animation: 150,
                                handle: '.drag-handle-chapter',
                                onEnd: function(evt) {
                                    console.log('📍 Drag terminé, updating order...');
                                    updateChapterOrder();
                                }
                            });
                            console.log('✅ Sortable activé');
                        } else {
                            console.error('❌ Sortable non disponible');
                        }
                        
                    } else {
                        // Désactiver le mode réorganisation
                        this.classList.remove('bg-green-500', 'hover:bg-green-700');
                        this.classList.add('bg-blue-500', 'hover:bg-blue-700');
                        this.textContent = 'Réorganiser les chapitres';
                        
                        // Cacher les drag handles
                        document.querySelectorAll('.drag-handle-chapter').forEach(handle => {
                            handle.classList.add('hidden');
                        });
                        
                        console.log('✅ Mode réorganisation désactivé');
                        
                        // Recharger la page pour voir l'ordre mis à jour
                        location.reload();
                    }
                });
                
                console.log('✅ Event listener ajouté au bouton');
            } else {
                console.error('❌ Bouton reorder-chapters-button non trouvé');
            }
        });
        
        function updateChapterOrder() {
            const chapters = document.querySelectorAll('#chapters-container .chapter');
            const orderData = [];
            
            chapters.forEach((chapter, index) => {
                const chapterId = chapter.id.replace('chapter-', '');
                orderData.push({
                    id: chapterId,
                    order: index + 1
                });
            });
            
            console.log('📊 Nouveau ordre des chapitres:', orderData);
            
            // Envoyer la nouvelle organisation au serveur
            fetch('{{ route('ordering.reorderChapters') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    class_id: {{ $classe->id }},
                    chapter_orders: orderData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('✅ Chapitres réorganisés avec succès');
                } else {
                    console.error('❌ Erreur lors de la réorganisation:', data.message);
                }
            })
            .catch(error => {
                console.error('❌ Erreur:', error);
            });
        }
    </script>
@endsection
