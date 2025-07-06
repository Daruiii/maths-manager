@extends('layouts.app')

@section('title', 'Accueil - Maths Manager')
@section('meta_description', "Plateforme de gestion de maths : exercices, quizz, fiches, DS, progression et corrections personnalisées pour lycéens.")
@section('canonical', url()->current())

@section('content')
    <div class="container mx-auto">
        <div class="quote-box">
            <p class="quote-text">“L'imagination est plus importante que le savoir”</p>
            <p class="quote-author">(Albert Einstein)</p>
        </div>

        @auth
            <div class="flex flex-col md:flex-row justify-center w-11/12 mx-auto p-6 rounded-lg gap-2 mb-8 ">
                <div class="flex flex-col w-full md:w-full p-6 rounded-lg">
                    {{-- <h2 class="text-base font-bold text-left md:text-center">Tableau de bord</h2> --}}
                    <div class="flex justify-center flex-wrap gap-4 mt-4">
                        @if (Auth::user()->role == 'admin')
                            <x-homeAdmin />
                        @elseif (Auth::user()->role == 'teacher')
                            <x-homeTeacher />
                        @else
                            <x-homeStudent totalDS="{{ $totalDS }}" notStartedDS="{{ $notStartedDS }}"
                                inProgressDS="{{ $inProgressDS }}" sentDS="{{ $sentDS }}" correctedDS="{{ $correctedDS }}"
                                averageGrade="{{ $averageGrade }}" goodAnswers="{{ $goodAnswers }}"
                                badAnswers="{{ $badAnswers }}" scores="{{ $scores }}" />
                        @endif
                    </div>
                </div>
            </div>
        @endauth
        @guest
            @if (isset($whoamiContent) && isset($introContent))
                <x-homeGuest whoamiTitle="{{ $whoamiContent->title }}" whoamiContent="{{ $whoamiContent->content }}"
                    introTitle="{{ $introContent->title }}" introContent="{{ $introContent->content }}"
                    whoamiImage="{{ $whoamiContent->image }}" />
            @else
                <p>Some content is missing. Please check back later.</p>
            @endif
        @endguest
    </div>
@endsection
