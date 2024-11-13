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
                                    <img src="{{ asset('storage/' . $correctionPicture) }}" alt="Image de la correction">
                                    <p class="text-center text-xs md:text-sm">Image {{ $index + 1 }}</p>
                                </a>
                            </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="flex flex-col justify-center items-center w-full h-full p-4">
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
