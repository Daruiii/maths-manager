@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <x-back-btn path="" > Retour</x-back-btn>
        <div class="flex flex-col w-full md:w-3/4 bg-[#FBF7F0] p6 rounded-lg my-8">
            <style>
                .p6 {
                    padding: 1.5rem;
                }
                @media (max-width: 768px) {
                    .p6 {
                        padding: 0;
                    }
                }
            </style>
            {{-- adm btn --}}
            @auth @if (Auth::user()->role === 'admin')
                <div class="flex gap-2 mb-4">
                    <x-button-add href="{{ route('recap.createPart', $recap->id) }}">
                        Partie
                    </x-button-add>
                    <button id="toggle-reorder-mode" class="bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded">
                        Réorganiser
                    </button>
                </div>
            @endif @endauth
            <div class="flex justify-center items-center">
                <h2 class="p-2 text-xs md:text-base font-bold text-center border border-black w-2/3">Fiche récapitulative -
                    {{ $recap->chapter->title }}</h2>
            </div>

            <div class="flex flex-col aligns-center md:p-2 my-2 w-full">
                @foreach ($recap->recapParts as $index => $recapPart)
                    <div class="flex flex-col justify-center w-full md:p-2 my-2 ">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <h3 class="ms-12 text-base md:text-lg font-bold break-words"> {{ $index + 1 }}.
                                    {{ $recapPart->title }}</h3>
                                @auth @if (Auth::user()->role === 'admin')
                                    <div class="flex flex-col gap-0.5 part-move-buttons hidden">
                                        <button onclick="window.recapDragDrop.movePartUp({{ $recapPart->id }}, this)"
                                                class="p-1 rounded hover:bg-gray-200 transition-colors"
                                                title="Déplacer vers le haut">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        </button>
                                        <button onclick="window.recapDragDrop.movePartDown({{ $recapPart->id }}, this)"
                                                class="p-1 rounded hover:bg-gray-200 transition-colors"
                                                title="Déplacer vers le bas">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif @endauth
                            </div>
                            @auth @if (Auth::user()->role === 'admin')
                                <div class="flex items-center">
                                    <x-button-add href="{{ route('recapPartBlock.createBlock', $recapPart->id) }}">
                                        Bloc
                                    </x-button-add>
                                    <x-button-edit href="{{ route('recapPart.edit', $recapPart->id) }}" />
                                    <x-button-delete href="{{ route('recapPart.destroy', $recapPart->id) }}" entity="cette partie" entityId="part{{$recapPart->id}}" />
                                </div>
                            @endif @endauth
                        </div>
                        <p class="ms-12 text-xs md:text-sm w-2/3 break-words"> {{ $recapPart->description }}</p>
                        <div class="flex flex-col justify-center items-center w-full sortable-blocks" data-recap-part-id="{{ $recapPart->id }}">
                        @foreach ($recapPart->recapPartBlocks as $recapPartBlock)
                            @php
                                if ($recapPartBlock->theme === 'Théorèmes') {
                                    $recapPartBlock->theme = '#E35F53';
                                } elseif ($recapPartBlock->theme === 'Définitions') {
                                    $recapPartBlock->theme = '#4896ac';
                                } elseif ($recapPartBlock->theme === 'Lemme') {
                                    $recapPartBlock->theme = '#65a986';
                                } elseif ($recapPartBlock->theme === 'Remarque') {
                                    $recapPartBlock->theme = '#bababa';
                                }
                            @endphp
                            <div x-data="{ showExample: false, showDemonstration: false, showRemarque: false }" class="flex flex-col justify-center items-center w-full md:w-10/12 block-item" data-block-id="{{ $recapPartBlock->id }}">
                                <div class="flex flex-row items-center w-full justify-start gap-2">
                                    @auth @if (Auth::user()->role === 'admin')
                                        <div class="drag-handle cursor-move p-1 hover:bg-gray-100 rounded hidden transition-colors" title="Glisser pour réorganiser">
                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 3C9 2.44772 8.55228 2 8 2C7.44772 2 7 2.44772 7 3V21C7 21.5523 7.44772 22 8 22C8.55228 22 9 21.5523 9 21V3Z"/>
                                                <path d="M17 3C17 2.44772 16.5523 2 16 2C15.4477 2 15 2.44772 15 3V21C15 21.5523 15.4477 22 16 22C16.5523 22 17 21.5523 17 21V3Z"/>
                                            </svg>
                                        </div>
                                    @endif @endauth
                                    <h5 class="recap-title text-xs md:text-sm font-bold text-white text-center p-1 w-auto rounded-lg my-2"
                                        style="background-color: {{ $recapPartBlock->theme }};">
                                        {{ $recapPartBlock->title }}</h5>
                                    @auth @if (Auth::user()->role === 'admin')
                                        <div class="relative inline-block" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                    type="button"
                                                    class="p-1.5 rounded hover:bg-gray-100 transition-colors"
                                                    title="Déplacer vers une autre partie">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12M8 12h12M8 17h12M3 7h.01M3 12h.01M3 17h.01"></path>
                                                </svg>
                                            </button>
                                            <div x-show="open"
                                                 @click.away="open = false"
                                                 x-transition
                                                 class="absolute left-0 mt-1 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                                <div class="py-1">
                                                    @foreach ($recap->recapParts as $part)
                                                        @if($part->id !== $recapPart->id)
                                                            <form action="{{ route('recapPartBlock.moveToPart', $recapPartBlock->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="recap_part_id" value="{{ $part->id }}">
                                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                    {{ $part->title }}
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <x-button-edit href="{{ route('recapPartBlock.edit', $recapPartBlock->id) }}" />
                                        <x-button-delete href="{{ route('recapPartBlock.destroy', $recapPartBlock->id) }}"
                                            entity="ce bloc" entityId="block{{$recapPartBlock->id}}" />
                                    @endif @endauth
                                </div>
                                <div class="mb-8 bg-white rounded-lg box-shadow shadow-xl w-full"
                                    style="border : 2px solid {{ $recapPartBlock->theme }}">
                                    <div class="w-full py-2 px-4 my-2 bloc-content text-sm cmu-serif break-words">
                                       {!! $recapPartBlock->content !!}
                                    </div>

                                    {{-- Exemple, Démonstration, Remarque --}}
                                    <div class="border-t w-full p-2 rounded-b-lg border-gray-300">
                                        <div class="flex justify-between items-center flex-wrap gap-2">
                                            <div class="flex gap-3">
                                                @if ($recapPartBlock->example)
                                                    <button @click="showExample = !showExample"
                                                        class="flex row text-xs font-bold">
                                                        Exemple
                                                        <svg :class="{ 'rotate-180': !showExample }"
                                                            class="transition-transform" width="15px" height="15px"
                                                            viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                            </g>
                                                            <g id="SVGRepo_iconCarrier">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M7.00003 15.5C6.59557 15.5 6.23093 15.2564 6.07615 14.8827C5.92137 14.509 6.00692 14.0789 6.29292 13.7929L11.2929 8.79289C11.6834 8.40237 12.3166 8.40237 12.7071 8.79289L17.7071 13.7929C17.9931 14.0789 18.0787 14.509 17.9239 14.8827C17.7691 15.2564 17.4045 15.5 17 15.5H7.00003Z"
                                                                    fill="#000000"></path>
                                                            </g>
                                                        </svg>
                                                    </button>
                                                @endif
                                                @if ($recapPartBlock->demonstration)
                                                    <button @click="showDemonstration = !showDemonstration"
                                                        class="flex row text-xs font-bold">
                                                        Démonstration
                                                        <svg :class="{ 'rotate-180': !showDemonstration }"
                                                            class="transition-transform" width="15px" height="15px"
                                                            viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                            </g>
                                                            <g id="SVGRepo_iconCarrier">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M7.00003 15.5C6.59557 15.5 6.23093 15.2564 6.07615 14.8827C5.92137 14.509 6.00692 14.0789 6.29292 13.7929L11.2929 8.79289C11.6834 8.40237 12.3166 8.40237 12.7071 8.79289L17.7071 13.7929C17.9931 14.0789 18.0787 14.509 17.9239 14.8827C17.7691 15.2564 17.4045 15.5 17 15.5H7.00003Z"
                                                                    fill="#000000"></path>
                                                            </g>
                                                        </svg>
                                                    </button>
                                                @endif
                                                @if ($recapPartBlock->remarque)
                                                    <button @click="showRemarque = !showRemarque"
                                                        class="flex row text-xs font-bold">
                                                        Remarque
                                                        <svg :class="{ 'rotate-180': !showRemarque }"
                                                            class="transition-transform" width="15px" height="15px"
                                                            viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                            </g>
                                                            <g id="SVGRepo_iconCarrier">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M7.00003 15.5C6.59557 15.5 6.23093 15.2564 6.07615 14.8827C5.92137 14.509 6.00692 14.0789 6.29292 13.7929L11.2929 8.79289C11.6834 8.40237 12.3166 8.40237 12.7071 8.79289L17.7071 13.7929C17.9931 14.0789 18.0787 14.509 17.9239 14.8827C17.7691 15.2564 17.4045 15.5 17 15.5H7.00003Z"
                                                                    fill="#000000"></path>
                                                            </g>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                            @if ($recapPartBlock->subchapter_id)
                                                <x-button-training href="{{ route('subchapter.show', $recapPartBlock->subchapter_id) }}">
                                                    S'entraîner
                                                </x-button-training>
                                            @endif
                                        </div>

                                        {{-- Exemple content --}}
                                        @if ($recapPartBlock->example)
                                            <div x-show="showExample" class="bg-gray-200 w-full p-2 rounded-lg mt-2">
                                                <div class="example-content text-sm p-4 cmu-serif">
                                                    {!! $recapPartBlock->example !!}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Démonstration content --}}
                                        @if ($recapPartBlock->demonstration)
                                            <div x-show="showDemonstration" class="bg-blue-50 w-full p-2 rounded-lg mt-2">
                                                <div class="demonstration-content text-sm p-4 cmu-serif">
                                                    {!! $recapPartBlock->demonstration !!}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Remarque content --}}
                                        @if ($recapPartBlock->remarque)
                                            <div x-show="showRemarque" class="bg-yellow-50 w-full p-2 rounded-lg mt-2">
                                                <div class="remarque-content text-sm p-4 cmu-serif">
                                                    {!! $recapPartBlock->remarque !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
                     <!-- Bouton "Back to the top" -->
        <x-button-back-top />
        </div>
    </div>

    {{-- Recap drag & drop system --}}
    @auth @if (Auth::user()->role === 'admin')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        <script src="{{ asset('js/recap-drag-drop.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.recapDragDrop = new window.RecapDragDrop({{ $recap->id }});
                window.recapDragDrop.init();
            });
        </script>
    @endif @endauth
@endsection

