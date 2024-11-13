@extends('layouts.app')
{{-- condition status --}}
{{-- @if ($correctionRequest->status == 'corrected')
<div class="bg-green-200 rounded-lgp-0.5 md:p-2">
    <h1 class="text-xs md:text-sm font-semibold text-center">Corrigé</h1>
</div>
@else
<div class="bg-yellow-200 rounded-lg p-0.5 md:p-2">
    <h1 class="text-xs md:text-sm font-semibold text-center">Demande en attente</h1>
</div>
@endif --}}

{{-- created_at --}}
{{-- <h1 class="text-xs md:text-sm font-semibold">{{ $correctionRequest->created_at->format('d/m/Y') }}</h1> --}}

{{-- user avatar + name --}}
{{-- <a href="#" class="flex flex-row items-center gap-1 hover:brightness-50 transition duration-300">
    @if (Str::startsWith($correctionRequest->user->avatar, 'http'))
        <img src="{{ $correctionRequest->user->avatar }}"
            class="w-6 h-6 md:w-8 md:h-8 rounded-full border border-black object-cover"alt="Profile Picture">
    @else
        <img src="{{ asset('storage/images/' . $correctionRequest->user->avatar) }}"
            class="w-6 h-6 md:w-8 md:h-8 rounded-full border border-black object-cover"
            alt="Profile Picture">
    @endif
    <h2 class="text-xs md:text-sm font-semibold">{{ $correctionRequest->user->name }}</h2>
</a> --}}

{{-- DS show --}}
{{-- <a href="{{ route('ds.show', $correctionRequest->ds_id) }}" class="underline text-xs md:text-sm">DS n°{{ $correctionRequest->ds_id }}</a> --}}
{{-- images de la demande --}}
{{-- <div class="image-carousel">
    @foreach ($pictures as $picture)
        <div class="item">
            <a href="{{ asset('storage/' . $picture) }}" data-fancybox="gallery">
                <img src="{{ asset('storage/' . $picture) }}" alt="Image de la demande de correction">
            </a>
        </div>
    @endforeach
</div> --}}

{{-- boutons modifier ou corriger --}}
{{-- @auth
    @if (Auth::user()->role == 'teacher' || Auth::user()->role == 'admin')
        @if ($correctionRequest->status == 'corrected')
            <a href="{{ route('correctionRequest.correctForm', $correctionRequest->ds_id) }}"
                class="text-xs md:text-sm p-1 md:p-2 bg-green-200 rounded-lg hover:bg-green-300 my-2">
                Modifier la correction</a>
        @else
            <a href="{{ route('correctionRequest.correctForm', $correctionRequest->ds_id) }}"
                class="text-xs md:text-sm p-1 md:p-2 bg-green-200 rounded-lg hover:bg-green-300 my-2">Corriger</a>
        @endif
    @endif
@endauth --}}

{{-- note --}}
{{-- @if ($correctionRequest->grade < 10)
<h3 class="text-center text-red-500 text-sm md:text-xl">{{ $correctionRequest->grade }}/20</h3>
@else
<h3 class="text-center text-green-500 text-sm md:text-xl">{{ $correctionRequest->grade }}/20</h3>
@endif --}}

{{-- condition message correction --}}
{{-- @if ($correctionRequest->correction_message)
<p class="text-xs md:text-sm">{{ $correctionRequest->correction_message }}</p>
@endif --}}

{{-- images de la correction --}}
{{-- @if ($correctedPictures)
    <div class="image-carousel">
        @foreach ($correctedPictures as $correctionPicture)
            <div class="item">
                <a href="{{ asset('storage/' . $correctionPicture) }}" data-fancybox="gallery2">
                    <img src="{{ asset('storage/' . $correctionPicture) }}" alt="Image de la correction">
                </a>
            </div>
        @endforeach
    </div>
@endif --}}

{{-- condition corrected --}}
{{-- @if ($correctionRequest->status == 'corrected')

@endif --}}

@section('content')
    <div class="container mx-auto px-4 md:px-0">
        <x-back-btn path=""> Retour</x-back-btn>
        <div class="flex flex-col md:flex-row align-center items-start justify-between my-5 w-full md:w-4/5  gap-2">
            <div
                class="flex flex-col items-start justify-start w-full md:w-1/2 h-96 max-h-96 bg-white rounded-lg box-shadow shadow-sm">
                <div class="pr-12 bg-yellow-100" style="border-radius : 2rem 0 10rem 0 ;">
                    <h1 class="text-black text-xl font-bold px-4 py-1">Demande</h1>
                </div>
                <div class="flex flex-row justify-between items-center w-full p-4">
                    <a href="#" class="flex flex-row items-center gap-1 hover:brightness-50 transition duration-300">
                        @if (Str::startsWith($correctionRequest->user->avatar, 'http'))
                            <img src="{{ $correctionRequest->user->avatar }}"
                                class="w-6 h-6 md:w-8 md:h-8 rounded-full border border-black object-cover"alt="Profile Picture">
                        @else
                            <img src="{{ asset('storage/images/' . $correctionRequest->user->avatar) }}"
                                class="w-6 h-6 md:w-8 md:h-8 rounded-full border border-black object-cover"
                                alt="Profile Picture">
                        @endif
                        <h2 class="text-xs md:text-sm font-semibold">{{ $correctionRequest->user->name }}</h2>
                    </a>
                    <a href="{{ route('ds.show', $correctionRequest->ds_id) }}" class="underline text-xs md:text-sm">DS
                        n°{{ $correctionRequest->ds_id }}</a>
                </div>
                <div class="image-carousel">
                    @foreach ($pictures as $index => $picture)
                        <div class="item">
                            <a href="{{ asset('storage/' . $picture) }}" data-fancybox="gallery"
                                class="w-full flex flex-row items-center justify-start">
                                <img src="{{ asset('storage/' . $picture) }}" alt="Image de la demande de correction">
                                <p class="text-center text-xs md:text-sm">Image {{ $index + 1 }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
                {{-- display the comment --}}
                @if ($correctionRequest->message)
                    <div class="flex flex-col justify-center items-center w-full h-full p-4">
                        <h1 class="text-xs md:text-sm font-semibold">Commentaire</h1>
                        <p class="text-xs md text-sm break-words">{{ $correctionRequest->message }}</p>
                    </div>
                @endif
                <div class="h-full w-full flex flex-row justify-end align-end items-end">
                    <h1 class="p-2 text-xs md:text-sm font-semibold">
                        {{ $correctionRequest->created_at->format('d/m/Y') }}</h1>
                </div>
            </div>
            <div
                class="flex flex-col items-start justify-start w-full md:w-1/2 h-96 max-h-96 bg-white rounded-lg box-shadow shadow-sm">
                <div class="pr-12 bg-green-100" style="border-radius : 2rem 0 10rem 0 ;">
                    <h1 class="text-black text-xl font-bold px-4 py-1">Correction</h1>
                </div>
                @if ($correctionRequest->status == 'corrected')
                    <div class="flex flex-row justify-between items-center w-full p-4">
                        <a href="#"
                            class="flex flex-row items-center gap-1 hover:brightness-50 transition duration-300">
                            @if (Str::startsWith($corrector->avatar, 'http'))
                                <img src="{{ $corrector->avatar }}"
                                    class="w-6 h-6 md:w-8 md:h-8 rounded-full border border-black object-cover"alt="Profile Picture">
                            @else
                                <img src="{{ asset('storage/images/' . $corrector->avatar) }}"
                                    class="w-6 h-6 md:w-8 md:h-8 rounded-full border border-black object-cover"
                                    alt="Profile Picture">
                            @endif
                            <h2 class="text-xs md:text-sm font-semibold">{{ $corrector->name }}</h2>
                        </a>
                        @auth
                            @if (Auth::user()->role == 'teacher' || Auth::user()->role == 'admin')
                                <a href="{{ route('correctionRequest.correctForm', $correctionRequest->ds_id) }}"
                                    class="text-xs md:text-sm p-1 bg-green-200 rounded-lg hover:bg-green-300">
                                    Modifier la correction</a>
                            @endif
                        @endauth
                    </div>
                    <div class="image-carousel">
                        @if ($correctedPictures)
                            @foreach ($correctedPictures as $index => $correctionPicture)
                                <div class="item">
                                    <a href="{{ asset('storage/' . $correctionPicture) }}" data-fancybox="gallery2"
                                        class="w-full flex flex-row items-center justify-start">
                                        <img src="{{ asset('storage/' . $correctionPicture) }}"
                                            alt="Image de la correction">
                                        <p class="text-center text-xs md:text-sm">Image {{ $index + 1 }}</p>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="flex flex-col justify-center items-center w-full h-full p-4 gap-4">
                        @if ($correctionRequest->correction_pdf)
                        <div class="flex items-center gap-4">
                            <h2 class="text-xs md:text-sm font-semibold">Correction PDF :</h2>
                            
                            {{-- Icône pour ouvrir le PDF dans un nouvel onglet --}}
                            <a href="{{ asset('storage/' . $correctionRequest->correction_pdf) }}" target="_blank" 
                               class="text-blue-500 flex items-center gap-1 hover:underline" title="Ouvrir dans un nouvel onglet">
                               <svg fill="#000000" width="15px" height="15px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" d="M5,2 L7,2 C7.55228475,2 8,2.44771525 8,3 C8,3.51283584 7.61395981,3.93550716 7.11662113,3.99327227 L7,4 L5,4 C4.48716416,4 4.06449284,4.38604019 4.00672773,4.88337887 L4,5 L4,19 C4,19.5128358 4.38604019,19.9355072 4.88337887,19.9932723 L5,20 L19,20 C19.5128358,20 19.9355072,19.6139598 19.9932723,19.1166211 L20,19 L20,17 C20,16.4477153 20.4477153,16 21,16 C21.5128358,16 21.9355072,16.3860402 21.9932723,16.8833789 L22,17 L22,19 C22,20.5976809 20.75108,21.9036609 19.1762728,21.9949073 L19,22 L5,22 C3.40231912,22 2.09633912,20.75108 2.00509269,19.1762728 L2,19 L2,5 C2,3.40231912 3.24891996,2.09633912 4.82372721,2.00509269 L5,2 L7,2 L5,2 Z M21,2 L21.081,2.003 L21.2007258,2.02024007 L21.2007258,2.02024007 L21.3121425,2.04973809 L21.3121425,2.04973809 L21.4232215,2.09367336 L21.5207088,2.14599545 L21.5207088,2.14599545 L21.6167501,2.21278596 L21.7071068,2.29289322 L21.7071068,2.29289322 L21.8036654,2.40469339 L21.8036654,2.40469339 L21.8753288,2.5159379 L21.9063462,2.57690085 L21.9063462,2.57690085 L21.9401141,2.65834962 L21.9401141,2.65834962 L21.9641549,2.73400703 L21.9641549,2.73400703 L21.9930928,2.8819045 L21.9930928,2.8819045 L22,3 L22,3 L22,9 C22,9.55228475 21.5522847,10 21,10 C20.4477153,10 20,9.55228475 20,9 L20,5.414 L13.7071068,11.7071068 C13.3466228,12.0675907 12.7793918,12.0953203 12.3871006,11.7902954 L12.2928932,11.7071068 C11.9324093,11.3466228 11.9046797,10.7793918 12.2097046,10.3871006 L12.2928932,10.2928932 L18.584,4 L15,4 C14.4477153,4 14,3.55228475 14,3 C14,2.44771525 14.4477153,2 15,2 L21,2 Z"></path> </g></svg>
                                <span class="hidden md:inline">Ouvrir</span>
                            </a>
                            
                            {{-- Icône pour télécharger le PDF --}}
                            <a href="{{ asset('storage/' . $correctionRequest->correction_pdf) }}" download 
                               class="text-blue-500 flex items-center gap-1 hover:underline" title="Télécharger le PDF">
                               <svg width="18px" height="18px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M17 17H17.01M17.4 14H18C18.9319 14 19.3978 14 19.7654 14.1522C20.2554 14.3552 20.6448 14.7446 20.8478 15.2346C21 15.6022 21 16.0681 21 17C21 17.9319 21 18.3978 20.8478 18.7654C20.6448 19.2554 20.2554 19.6448 19.7654 19.8478C19.3978 20 18.9319 20 18 20H6C5.06812 20 4.60218 20 4.23463 19.8478C3.74458 19.6448 3.35523 19.2554 3.15224 18.7654C3 18.3978 3 17.9319 3 17C3 16.0681 3 15.6022 3.15224 15.2346C3.35523 14.7446 3.74458 14.3552 4.23463 14.1522C4.60218 14 5.06812 14 6 14H6.6M12 15V4M12 15L9 12M12 15L15 12" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                <span class="hidden md:inline">Télécharger</span>
                            </a>
                        </div>
                    @endif
                    
                        @if ($correctionRequest->correction_message)
                            <p class="text-xs md text-sm break-words">{{ $correctionRequest->correction_message }}</p>
                        @endif
                        @if ($correctionRequest->grade < 10)
                            <h3 class="text-center text-red-500 text-sm md:text-xl">{{ $correctionRequest->grade }}/20</h3>
                        @else
                            <h3 class="text-center text-green-500 text-sm md:text-xl">{{ $correctionRequest->grade }}/20
                            </h3>
                        @endif
                    </div>
                    <div class="h-full w-full flex flex-row justify-end align-end items-end">
                        <h1 class="p-2 text-xs md:text-sm font-semibold">
                            {{ $correctionRequest->updated_at->format('d/m/Y') }}</h1>
                    </div>
                @else
                    {{-- en attente de correction centré verticalement --}}
                    <div class="w-full h-full min-h-64 grid place-items-center">
                        <x-wait-loader></x-wait-loader>
                    </div>
                    {{-- bouton modifier en bas --}}
                    @auth
                        @if (Auth::user()->role == 'teacher' || Auth::user()->role == 'admin')
                            <div class="h-full w-full flex flex-row justify-center align-end items-end p-2">
                                <a href="{{ route('correctionRequest.correctForm', $correctionRequest->ds_id) }}"
                                    class="text-xs md:text-sm p-1 bg-green-200 rounded-lg hover:bg-green-300">
                                    Corriger</a>
                            </div>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var numberOfPictures = {{ count($pictures) }};
            console.log(numberOfPictures);
            $('.owl-picture').owlCarousel({
                loop: false,
                margin: 10,
                nav: true,
                dots: false,
                items: numberOfPictures
            });
        });
    </script>
@endsection
