@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div
            class="flex flex-col align-center items-center justify-center my-5 bg-white w-11/12 md:w-4/5 rounded-lg box-shadow shadow-xl">
            {{-- titre demande de correction --}}
            {{-- studen name on left --}}
            {{-- student name --}}
            <div class="flex items-center w-full justify-between p-0.5 md:p-2">
                <div class="bg-gray-200 rounded-lg p-0.5 md:p-2">
                    <h1 class="text-xs md:text-sm font-semibold">{{ $correctionRequest->created_at->format('d/m/Y') }}</h1>
                </div>
                @if ($correctionRequest->status == 'corrected')
                    <div class="bg-green-200 rounded-lgp-0.5 md:p-2">
                        <h1 class="text-xs md:text-sm font-semibold text-center">Corrigé</h1>
                    </div>
                @else
                    <div class="bg-yellow-200 rounded-lg p-0.5 md:p-2">
                        <h1 class="text-xs md:text-sm font-semibold text-center">Demande en attente</h1>
                    </div>
                @endif
                <a href="#" class="flex flex-row items-center gap-1 hover:brightness-50 transition duration-300  ">
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
            </div>

            <a href="{{ route('ds.show', $correctionRequest->ds_id) }}" class="underline text-xs md:text-sm">DS
                n°{{ $correctionRequest->ds_id }}</a>
            {{-- @if ($correctionRequest->correction_message)
                    <p><strong>Message de correction:</strong> {{ $correctionRequest->correction_message }}</p>
                    @endif --}}
            {{-- @if ($correctionRequest->status == 'corrected')
                    <p><strong>Note attribuée:</strong> {{ $correctionRequest->grade }}/20</p>
                    @endif --}}


            {{-- texte images de la demande --}}
            <h2 class="text-xs md:text-sm font-semibold text-start w-full p-0.5 md:p-2">Images de la demande :</h2>
            {{-- images de la demande --}}
            <div class="image-carousel">
                @foreach ($pictures as $picture)
                    <div class="item">
                        <a href="{{ asset('storage/' . $picture) }}" data-fancybox="gallery">
                            <img src="{{ asset('storage/' . $picture) }}" alt="Image de la demande de correction">
                        </a>
                    </div>
                @endforeach
            </div>
            @auth
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
            @endauth
            @if ($correctionRequest->status == 'corrected')
                <h2 class="text-sm md:text-xl font-semibold w-full p-0.5 md:p-2 text-center">Correction :</h2>

                @if ($correctionRequest->grade < 10)
                    <h3 class="text-center text-red-500 text-sm md:text-xl">{{ $correctionRequest->grade }}/20</h3>
                @else
                    <h3 class="text-center text-green-500 text-sm md:text-xl">{{ $correctionRequest->grade }}/20</h3>
                @endif
                @if ($correctionRequest->correction_message)
                    <p class="text-xs md:text-sm">{{ $correctionRequest->correction_message }}</p>
                @endif
                {{-- texte images de la correction --}}
                <h2 class="text-xs md:text-sm font-semibold text-start w-full p-0.5 md:p-2">Images de la correction :</h2>
                {{-- images de la correction --}}
                @if ($correctedPictures)
                    <div class="image-carousel">
                        @foreach ($correctedPictures as $correctionPicture)
                            <div class="item">
                                <a href="{{ asset('storage/' . $correctionPicture) }}" data-fancybox="gallery2">
                                    <img src="{{ asset('storage/' . $correctionPicture) }}" alt="Image de la correction">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
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
