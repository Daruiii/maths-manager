@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div
            class="flex flex-col align-center items-center justify-center my-5 bg-[#FBF7F0] w-4/5 rounded-lg box-shadow shadow-xl">
            <div class="flex items-start justify-between w-full">
                <div class="flex items-start justify-center align-start pr-12"
                    style="border-radius : 2rem 0 10rem 0 ; background-color: {{ $subchapter->chapter->theme }};">
                    <h1 class="text-[#FBF7F0] text-xl font-bold px-4 py-1">{{ $subchapter->title }}</h1>
                </div>
                @auth
                    @if (Auth::user()->role === 'admin')
                        <div class="bg-green-500 hover:bg-green-700 text-white font-bold m-2 p-2 rounded">
                            <a href="{{ route('exercise.create', ['id' => $subchapter->id]) }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
            <h2 class="text-xs px-4 py-1 w-full cmu-ti">{{ $subchapter->description }}</h2>

            <div class=" p-5 flex flex-col align-center justify-start w-full">
                @foreach ($exercises as $index => $ex)
                    @php
                        $exerciseId = $ex->id;
                        $exerciseFiles = Storage::disk('public')->files("latex_output/exercise_{$exerciseId}/exercise");
                        $exercisePngFiles = array_filter($exerciseFiles, function ($file) {
                            return Str::endsWith($file, '.png');
                        });
                        $solutionFiles = Storage::disk('public')->files(
                            "latex_output/exercise_{$exerciseId}/correction",
                        );
                        $solutionPngFiles = array_filter($solutionFiles, function ($file) {
                            return Str::endsWith($file, '.png');
                        });
                    @endphp
                    <div x-data="{ showClue: false, showSolution: false }" class="mb-8 bg-white rounded-lg box-shadow shadow-xl w-full">
                        <div class="p-4">
                            @if ($ex->name)
                                <div class="flex row justify-end items-center h-2">
                                    {{-- <h2> #{{ $ex->id }}{{ $index + 1 }}</h2> --}}
                                    @auth
                                        @if (Auth::user()->role === 'admin')
                                            <div class="flex items-center space-x-2">
                                                <form action="{{ route('exercise.destroy', $ex->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 rounded-full text-red-500 hover:text-red-600"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice ?');">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('exercise.edit', ['id' => $ex->id]) }}" method="GET"
                                                    class="inline">
                                                    <button type="submit"
                                                        class="p-2 rounded-full text-red-500 hover:text-red-600">
                                                        <svg fill="#000000" class="h-3 w-3" version="1.1" id="Capa_1"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                            viewBox="0 0 348.882 348.882" xml:space="preserve">
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
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>

                                <div class="exercise-content text-sm px-4 cmu-serif">
                                    <h2 class="truncate font-bold text-sm exercise-title">Exercice {{ $ex->id }}.
                                        {{ $ex->name }}</h2>
                                    {!! $ex->statement !!}
                                </div>
                            @else
                                @auth
                                    @if (Auth::user()->role === 'admin')
                                        <div class="flex row justify-end items-center h-2">
                                            <form action="{{ route('exercise.destroy', $ex->id) }}" method="POST"
                                                class="inline">
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
                                            <form action="{{ route('exercise.edit', ['id' => $ex->id]) }}" method="GET"
                                                class="inline">
                                                <button type="submit" class="p-2 rounded-full text-red-500 hover:text-red-600">
                                                    <svg fill="#000000" class="h-3 w-3" version="1.1" id="Capa_1"
                                                        xmlns="http://www.w3.org/2000/svg"
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
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                                <div class="exercise-content text-sm px-4 cmu-serif">
                                    <span class="truncate font-bold text-sm exercise-title"> Exercice
                                        {{ $ex->id }}.</span> {!! $ex->statement !!}
                                </div>
                            @endif

                            {{-- @foreach ($exercisePngFiles as $pngFile)
                                <div class="pdf-container">
                                    <img src="{{ asset('storage/' . $pngFile) }}" alt="Exercice image" class="png"
                                        style="width: 500px; height: auto;">
                                </div>
                            @endforeach --}}
                        </div>
                        <div class="border-t w-full p-2 rounded-b-lg border-gray-300">
                            <div class="flex justify-between items-center">
                                @if ($ex->clue)
                                    <button @click="showClue = !showClue" class="dropdownCC flex row text-xs font-bold">
                                        Voir l'indice
                                        <svg :class="{ 'rotate-180': !showClue }" class="transition-transform"
                                            width="15px" height="15px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M7.00003 15.5C6.59557 15.5 6.23093 15.2564 6.07615 14.8827C5.92137 14.509 6.00692 14.0789 6.29292 13.7929L11.2929 8.79289C11.6834 8.40237 12.3166 8.40237 12.7071 8.79289L17.7071 13.7929C17.9931 14.0789 18.0787 14.509 17.9239 14.8827C17.7691 15.2564 17.4045 15.5 17 15.5H7.00003Z"
                                                    fill="#000000"></path>
                                            </g>
                                        </svg>
                                    </button>
                                @else
                                    <div class=""></div>
                                @endif
                                @if ($ex->solution)
                                    <button @click="showSolution = !showSolution"
                                        class="dropdownCC flex row text-xs font-bold">
                                        Voir la correction
                                        <svg :class="{ 'rotate-180': !showSolution }" class="transition-transform"
                                            width="15px" height="15px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
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

                            <div x-show="showClue" class="bg-[#D4D68D] w-full p-2 rounded-lg">
                                <h3 class="exercise-cc font-bold">Indice:</h3>
                                <div class="clue-content text-sm p-4 cmu-ti">
                                    {!! $ex->clue !!}
                                </div>
                            </div>
                            <div x-show="showSolution" class="exercise-cc bg-[#D68D8D] w-full p-2 rounded-lg">
                                <h3 class="exercise-cc font-bold">Correction:</h3>
                                <div class="solution-content text-sm p-4">
                                    {!! $ex->solution !!}
                                </div>
                                {{-- @foreach ($solutionPngFiles as $pngFile)
                                    <div class="pdf-container">
                                        <img src="{{ asset('storage/' . $pngFile) }}" alt="Solution image"
                                            class="png" style="width: 500px; height: auto;">
                                    </div>
                                @endforeach --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endsection
