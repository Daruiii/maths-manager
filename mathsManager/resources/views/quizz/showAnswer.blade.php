@extends('layouts.app')

@section('content')
<x-back-btn path="{{ route('end_quizz') }}">Quitter</x-back-btn>
<x-progress-bar currentQuestion="{{ session('currentQuestion') }}" totalQuestions="{{ count(session('questions')) }}" />

    <section class="container mx-auto slide-left">
        <div class="p-6 bg-white shadow-md rounded-md flex flex-col items-center justify-center mt-8">
            @if($answer->is_correct)
            <h1 class="text-xl font-bold mb-4">Bravo, votre réponse est correcte !</h1> {{-- ici, on mettre image réussite --}}

            <p class="mb-4 text-green-500 text-2xl font-bold score-increase">+1</p>
            @else
            <h1 class="text-xl font-bold mb-4 text-red-500">Désolé, votre réponse est incorrecte.</h1>
            <p class="w-full text-start text-sm mb-2">La bonne réponse était :</p>
            <div class="flex flex-col bg-white border border-green-200 shadow-sm rounded-xl p-4 md:p-5">
                <p class="cmu-serif clue-content">{!! $correctAnswer->answer !!}</p>
              </div>
            <p class="w-full text-start text-sm mb-2">Explication :</p>
            <p class="w-10/12 text-start cmu-serif clue-content break-words mb-4">{!! $correctAnswer->explanation !!}</p>
            @endif

            @if(session('currentQuestion') == count(session('questions')))
            {{-- <a href="{{ route('show_result') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md">Voir le score</a> --}}
            <x-button-result href="{{ route('show_result') }}">
                Voir le résultat
            </x-button-result>
            @else
            <x-button-next href="{{ route('show_question') }}">
                Question suivante
            </x-button-next>
            @endif
        </div>
    </section>

@endsection