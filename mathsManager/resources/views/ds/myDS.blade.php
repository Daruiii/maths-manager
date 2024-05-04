@extends('layouts.app')

@section('content')
    {{-- Titre de la page un peu sur la gauche avec ecrit "Mes devoirs" --}}
    {{-- un bouton pour générer un nouveau DS --}}
    <div class="container mx-auto mb-8">
        <div class="flex justify-start flex-col align-center w-9/12 mt-6 mb-4 ms-12">
            <h1 class="text-xl cmu-bold">Mes devoirs</h1>
            <div class="flex row justify-start align-center">
                @php
                    $last_ds = new DateTime(Auth::user()->last_ds_generated_at);
                @endphp
                @if (Auth::user()->last_ds_generated_at == null || date('Y-m-d') != $last_ds->format('Y-m-d'))
                    <a href="{{ route('ds.create') }}"
                        class="bg-blue-100 rounded-lg p-2 link w-44 text-center hover:bg-blue-200 shadow-md transition duration-300">
                        Générer un devoir
                    </a>
                @else
                    {{-- bouton disabled --}}
                    <button
                        class="bg-blue-100 rounded-lg p-2 link w-44 text-center hover:bg-blue-200 shadow-md transition duration-300"
                        disabled>
                        Générer un devoir
                    </button>
                @endif
            </div>
        </div>
        {{-- error session display in the middle --}}
        @if (session('error'))
            <div class="flex justify-center items-center w-8/12 h-10 bg-red-100 rounded-lg p-2 my-3">
                <h2 class="text-red-600">{{ session('error') }}</h2>
            </div>
        @endif
        <div class="flex row justify-center align-center flex-wrap gap-5 w-8/12">
            @foreach ($dsList as $index => $ds)
                <div class="ds-card">
                    <div class="ds-card-image"> {{-- admin --}}
                        {{-- @if (Auth::user()->role == 'admin')
                            <div class="flex justify-end px-3 flex-row-reverse items-center w-5/6 gap-2 ">
                                <x-button-edit href="{{ route('ds.edit', $ds->id) }}" />
                                <x-button-delete href="{{ route('ds.destroy', $ds->id) }}" entity="ce DS" />
                            </div>
                        @endif --}}
                        @if ($ds->status == 'not_started')
                            <div class="flex justify-center items-center w-full">
                                <x-button-arrow-start href="{{ route('ds.start', $ds->id) }}">
                                    {{ __('Commencer') }}
                                </x-button-arrow-start>
                            </div>
                        @elseif ($ds->status == 'ongoing')
                            <div class="flex justify-center items-center w-full">
                                <x-button-arrow-continue href="{{ route('ds.start', $ds->id) }}">
                                    {{ __('Continuer') }}
                                </x-button-arrow-continue>
                                {{-- <a href="{{ route('ds.start', $ds->id) }}" btn qui bouge mais bug en prod
                                    class=" flex justify-between items-center bg-[#fda054] px-3 py-3 rounded-lg mb-2 text-white tracking-wider hover:bg-[#dcb470] hover:scale-105 duration-500 w-[150px] text-xs">
                                    {{ __('Continuer') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-5 h-5 animate-bounce">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"></path>
                                    </svg>
                                </a> --}}
                            </div>
                        @elseif ($ds->status == 'finished')
                            <div class="flex justify-end items-center w-full">
                                <x-button-full-arrow-transition
                                    href="{{ route('correctionRequest.showCorrectionRequestForm', $ds->id) }}">
                                    {{ __('Envoyer pour correction') }}
                                </x-button-full-arrow-transition>
                            </div>
                        @elseif ($ds->status == 'sent')
                            <div class="flex justify-end items-center w-full">
                                <x-full-button-transition-top href="{{ route('correctionRequest.show', $ds->id) }}">
                                    {{ __('Voir ma demande') }}
                                </x-full-button-transition-top>
                            </div>
                        @elseif ($ds->status == 'corrected')
                            <div class="flex justify-end items-center w-full gap-1">
                                <x-button-transition-top href="{{ route('correctionRequest.show', $ds->id) }}">
                                    {{ __('Voir la correction') }}
                                </x-button-transition-top>
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-2 justify-center items-center mb-2 p-2">
                            @foreach ($ds->exercisesDS as $exercise)
                                <h3 class="text-xs cmu font-bold p-1 rounded-full text-center vertical-center hover:bg-blue-200 shadow-md transition duration-300 truncate max-w-1/2"
                                    style="background-color: {{ $exercise->multipleChapter->theme }};">
                                    {{ $exercise->multipleChapter->title }}</h3>
                            @endforeach
                        </div>
                    </div>
                    @php
                        $hours = 0;
                        $time = $ds->time;
                        while ($time > 60) {
                            $hours++;
                            $time -= 60;
                        }
                        // get the timer time hours:minutes:seconds
                        $timerHours = 0;
                        $timerMinutes = 0;
                        $timer = $ds->timer;
                        while ($timer > 3600) {
                            $timerHours++;
                            $timer -= 3600;
                        }
                        while ($timer > 60) {
                            $timerMinutes++;
                            $timer -= 60;
                        }
                    @endphp
                    <div
                        class="ds-card-description {{ $ds->status == 'not_started' ? 'bg-green-50' : '' }}
                        {{ $ds->status == 'ongoing' ? 'bg-orange-50' : '' }}
                        {{ $ds->status == 'finished' ? 'bg-blue-50' : '' }}
                        {{ $ds->status == 'sent' ? 'bg-gray-200' : '' }}
                        {{ $ds->status == 'corrected' ? 'bg-gray-200' : '' }}">
                        <p class="text-xs text-center font-bold"> {{ $ds->type_bac ? 'Type Bac' : 'Devoir' }} n°
                            {{ $index + 1 }}</p>
                        <div class="ds-text-body">
                            @if ($ds->status == 'corrected')
                                <div class="flex row justify-center items-center gap-1 border-y border-gray-300 mb-1">
                                    @if ($ds->correctionRequest->grade < 10)
                                        <h2 class="text-base text-red-500 sign-painter">
                                            {{ $ds->correctionRequest->grade }}/20</h2>
                                    @else
                                        <h2 class="text-base text-green-500 sign-painter">
                                            {{ $ds->correctionRequest->grade }}/20</h2>
                                    @endif
                                </div>
                            @elseif ($ds->status == 'sent')
                                <div class="flex row justify-center items-center gap-1 border-y border-gray-300 mb-1">
                                    <h2 class="text-base text-gray-500 sign-painter"> /20</h2>
                                </div>
                            @endif
                            <div class="flex row items-center gap-1">
                                <svg width="12px" height="12px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                    fill="none">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill="#000000" fill-rule="evenodd"
                                            d="M4 1a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V7.414A2 2 0 0017.414 6L13 1.586A2 2 0 0011.586 1H4zm0 2h7.586L16 7.414V17H4V3zm2 2a1 1 0 000 2h5a1 1 0 100-2H6zm-1 5a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h8a1 1 0 100-2H6z">
                                        </path>
                                    </g>
                                </svg>
                                <p class="text-xs">{{ $ds->exercises_number }}
                                    exercice{{ $ds->exercises_number > 1 ? 's' : '' }} </p>
                            </div>
                            <div class="flex row items-center gap-1">
                                <svg width="12px" height="12px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12ZM3.00683 12C3.00683 16.9668 7.03321 20.9932 12 20.9932C16.9668 20.9932 20.9932 16.9668 20.9932 12C20.9932 7.03321 16.9668 3.00683 12 3.00683C7.03321 3.00683 3.00683 7.03321 3.00683 12Z"
                                            fill="#0F0F0F"></path>
                                        <path
                                            d="M12 5C11.4477 5 11 5.44771 11 6V12.4667C11 12.4667 11 12.7274 11.1267 12.9235C11.2115 13.0898 11.3437 13.2343 11.5174 13.3346L16.1372 16.0019C16.6155 16.278 17.2271 16.1141 17.5032 15.6358C17.7793 15.1575 17.6155 14.5459 17.1372 14.2698L13 11.8812V6C13 5.44772 12.5523 5 12 5Z"
                                            fill="#0F0F0F"></path>
                                    </g>
                                </svg>
                                <p class="text-xs">{{ $hours ? $hours . 'h' : '' }} {{ $time ? $time . 'min' : '' }}</p>
                            </div>
                            @if ($ds->status == 'ongoing')
                                <div class="flex row items-center gap-1">
                                    <svg fill="#000000" width="12px" height="12px" viewBox="0 0 24 24" id="Layer_1"
                                        data-name="Layer 1" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M24,12a1,1,0,0,1-2,0A10.011,10.011,0,0,0,12,2a1,1,0,0,1,0-2A12.013,12.013,0,0,1,24,12Zm-8,1a1,1,0,0,0,0-2H13.723A2,2,0,0,0,13,10.277V7a1,1,0,0,0-2,0v3.277A1.994,1.994,0,1,0,13.723,13ZM1.827,6.784a1,1,0,1,0,1,1A1,1,0,0,0,1.827,6.784ZM2,12a1,1,0,1,0-1,1A1,1,0,0,0,2,12ZM12,22a1,1,0,1,0,1,1A1,1,0,0,0,12,22ZM4.221,3.207a1,1,0,1,0,1,1A1,1,0,0,0,4.221,3.207ZM7.779.841a1,1,0,1,0,1,1A1,1,0,0,0,7.779.841ZM1.827,15.216a1,1,0,1,0,1,1A1,1,0,0,0,1.827,15.216Zm2.394,3.577a1,1,0,1,0,1,1A1,1,0,0,0,4.221,18.793Zm3.558,2.366a1,1,0,1,0,1,1A1,1,0,0,0,7.779,21.159Zm14.394-5.943a1,1,0,1,0,1,1A1,1,0,0,0,22.173,15.216Zm-2.394,3.577a1,1,0,1,0,1,1A1,1,0,0,0,19.779,18.793Zm-3.558,2.366a1,1,0,1,0,1,1A1,1,0,0,0,16.221,21.159Z">
                                            </path>
                                        </g>
                                    </svg>
                                    <p class="text-xs">{{ $timerHours ? $timerHours . 'h' : '' }}
                                        {{ $timerMinutes ? $timerMinutes . 'min et' : '' }}
                                        {{ $timer ? $timer . 's' : '' }}
                                    </p>
                                </div>
                            @endif
                            <div class="flex row items-center gap-1">
                                @if ($ds->harder_exercises)
                                    <svg fill="#000000" width="12px" height="12px" viewBox="0 0 22 22" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <circle id="Oval" cx="11" cy="11" r="5.10714286"></circle>
                                        </g>
                                    </svg>
                                    <p class="text-xs">Mode difficile</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
