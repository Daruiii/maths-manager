@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <x-back-btn path="{{ route('classe.show', $recap->chapter->classe->level) }}" />
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
                <x-button-add href="{{ route('recap.createPart', $recap->id) }}">
                    Partie
                </x-button-add>
            @endif @endauth
            <div class="flex justify-center items-center">
                <h2 class="p-2 text-xs md:text-base font-bold text-center border border-black w-2/3">Fiche récapitulative -
                    {{ $recap->chapter->title }}</h2>
            </div>

            <div class="flex flex-col aligns-center md:p-2 my-2 w-full">
                @foreach ($recap->recapParts as $index => $recapPart)
                    <div class="flex flex-col justify-center w-full md:p-2 my-2 ">
                        <div class="flex justify-between items-center">
                            <h3 class="ms-12 text-xl md:text-2xl font-bold break-words"> {{ $index + 1 }}.
                                {{ $recapPart->title }}</h3>
                            @auth @if (Auth::user()->role === 'admin')
                                <div class="flex items-center">
                                    <x-button-add href="{{ route('recapPartBlock.createBlock', $recapPart->id) }}">
                                        Bloc
                                    </x-button-add>
                                    <x-button-edit href="{{ route('recapPart.edit', $recapPart->id) }}" />
                                    <x-button-delete href="{{ route('recapPart.destroy', $recapPart->id) }}" entity="cette partie" />
                                </div>
                            @endif @endauth
                        </div>
                        <p class="ms-12 text-xs md:text-sm w-2/3 break-words"> {{ $recapPart->description }}</p>
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
                            <div x-data="{ showExample: false }" class="flex flex-col justify-center items-center w-full">
                                <div class="flex flex-row items-center w-full justify-start gap-1">
                                    <h5 class="text-xs md:text-sm font-bold text-white text-center p-1 w-auto rounded-lg my-2"
                                        style="background-color: {{ $recapPartBlock->theme }};">
                                        {{ $recapPartBlock->title }}</h5>
                                    @auth @if (Auth::user()->role === 'admin')
                                        <x-button-edit href="{{ route('recapPartBlock.edit', $recapPartBlock->id) }}" />
                                        <x-button-delete href="{{ route('recapPartBlock.destroy', $recapPartBlock->id) }}"
                                            entity="ce bloc" />
                                    @endif @endauth
                                </div>
                                <div class="mb-8 bg-white rounded-lg box-shadow shadow-xl w-full"
                                    style="border : 2px solid {{ $recapPartBlock->theme }}">
                                    <div class="flex flex-col justify-center w-full p-2 my-2 bloc-content">
                                        <p class="p-2 cmu-serif text-base w-full break-words"> {!! $recapPartBlock->content !!}</p>
                                    </div>

                                    {{-- Exemple --}}
                                    <div class="border-t w-full p-2 rounded-b-lg border-gray-300">
                                        <div class="flex justify-between items-center">
                                            @if ($recapPartBlock->example)
                                                <button @click="showExample = !showExample"
                                                    class=" flex row text-xs font-bold">
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
                                        </div>
                                        <div x-show="showExample" class="bg-gray-200 w-full p-2 rounded-lg">
                                            {{-- <h3 class="exercise-cc font-bold">Exemple :</h3> --}}
                                            <div class="example-content  text-base p-4 cmu-ti">
                                                {!! $recapPartBlock->example !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
