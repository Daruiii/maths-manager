@extends('layouts.app')

@section('content')
    <div class="container mx-auto chapters-container">
        <div class="flex header">
            <div>
                <h1 class="title">{{ $classe->name }}</h1>
                <h2 class="cmu-serif subtitle">Chapitres</h2>
            </div>
            @auth
                @if (Auth::user()->role === 'admin')
                    <a href= "{{ route('chapter.create', $classe->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Ajouter un Chapitre
                    </a>
                @endif
            @endauth
        </div>
        <div class=" chapter-list">
            @foreach ($chapters as $indexChap => $chapter)
            @props(['color' => $chapter->theme])
                <div x-data="{ open: false, confirmDelete: false }" class="chapter bg-white rounded-lg p-2 mb-4" style="border-left: 5px solid {{ $chapter->theme }}; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);" x-cloak>
                    {{-- Chapitre Titre et Boutons d'Action pour Admin --}}
                    <div class="flex justify-between items-center">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-2/3 text-left chapter-title text-lg font-semibold text-gray-700">
                            <span class="truncate">{{ $indexChap + 1 }}. {{ $chapter->title }}</span>
                        </button>
                        <button <button @click="open = !open"
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
                                    <a href="{{ route('chapter.edit', $chapter->id) }}"
                                        class="p-2 rounded-full text-blue-500 hover:text-blue-600">
                                        {{-- Icône d'Édition --}}
                                        <svg fill="#000000" height="15px" width="15px" version="1.1" id="Capa_1"
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
                                    </a>
                                    <button @click="confirmDelete = true"
                                        class="p-2 rounded-full text-red-500 hover:text-red-600">
                                        {{-- Icône de Suppression --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        @endauth
                    </div>
                    <div x-show="open" x-cloak class="px-4 pt-2 pb-4">
                        <div class="flex items-start space-x-2 mb-4">
                            <div class="flex flex-col w-2/3 bg-gray-100 p-4 rounded-lg">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-lg font-semibold truncate">Exercices par thème :</h3>
                                    @auth
                                        @if (Auth::user()->role === 'admin')
                                            <a href="{{ route('subchapter.create', ['id' => $chapter->id]) }}"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                                <div class="space-y-2">
                                @foreach ($chapter->subchapters as $index => $subchapter)
                                    <div class="flex items-center justify-between px-2 border-b border-gray-200">
                                        <a href="{{ route('subchapter.show', $subchapter->id) }}" class="my-2 flex w-full items-center justify-between space-x-2 truncate hover:underline border-l-2 border-black pl-2">
                                            <span>{{$indexChap+1}}.{{$index+1}} - {{ $subchapter->title }}</span>
                                            <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g><g id="SVGRepo_iconCarrier"> <path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        </a>
                                        @auth
                                            @if (Auth::user()->role === 'admin')
                                                <a href="{{ route('subchapter.edit', $subchapter->id) }}"
                                                    class="p-2 rounded-full text-blue-500 hover:text-blue-600">
                                                    {{-- Icône d'Édition --}}
                                                    <svg fill="#000000" height="15px" width="15px" version="1.1"
                                                        id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 348.882 348.882"
                                                        xml:space="preserve">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                            stroke-linejoin="round"></g>
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
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('subchapter.destroy', $subchapter->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce sous-chapitre ?')"
                                                        class="p-2 rounded-full text-red-500 hover:text-red-600">
                                                        {{-- Icône de Suppression --}}
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                @endforeach
                                </div>
                            </div>
                            {{-- Boutons Quiz et Récap ici --}}
                            <div class="flex flex-col space-y-2 w-1/3">
                                <a href="#quiz"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                                    Quiz
                                </a>
                                <a href="#recap"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center">
                                    Récap
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- Popup de Confirmation de Suppression --}}
                    <div x-show="confirmDelete" @click.away="confirmDelete = false"
                        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center" x-cloak>
                        <div class="bg-white p-4 rounded-lg shadow-xl">
                            <p>Êtes-vous sûr de vouloir supprimer ce chapitre ?</p>
                            <div class="flex justify-end space-x-2 mt-4">
                                <button @click="confirmDelete = false"
                                    class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                                    Annuler
                                </button>
                                <form method="POST" action="{{ route('chapter.destroy', $chapter->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
