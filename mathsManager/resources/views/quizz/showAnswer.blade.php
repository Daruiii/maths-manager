@extends('layouts.app')

@section('content')
<x-back-btn path="{{ route('end_quizz') }}">Quitter</x-back-btn>
<x-progress-bar currentQuestion="{{ session('currentQuestion') }}" totalQuestions="{{ count(session('questions')) }}" />

    <section class="container mx-auto @if($answer->is_correct) slide-left @endif mb-8">
        
            @if($answer->is_correct)
            <div class="p-6 bg-white shadow-md rounded-md flex flex-col items-center justify-center mt-8">
            <h1 class="text-xl font-bold mb-4">Bravo, votre réponse est correcte !</h1> {{-- ici, on mettre image réussite --}}

            <p class="mb-4 text-green-500 text-2xl font-bold score-increase">+1</p>
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
            @else
            <div class=" flex flex-col items-center justify-center">
            <div class="text-center mt-4">
                <p class="text-sm mb-4">Question n°{{ session('currentQuestion') }}</p>
                <h1 class="text-base font-bold text-red-500">Désolé, votre réponse est incorrecte.</h1>
            </div>
            <div class="mt-4 flex flex-col items-center justify-center">
                <h1 class="text-sm mb-4 clue-content cmu-serif bg-white p-4 rounded-md w-full md:w-7/12 break-words">{!! $question->question !!}</h1>
                    <div class="mt-6 flex flex-wrap justify-center items-center gap-4 space-x-4">            
                            <x-radio-btn name="answer" id="answer{{ $answer->id }}" value="{{ $answer->id }}" disabled class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                                {!! $answer->answer !!}
                            </x-radio-btn>
                            <x-radio-btn name="correct_answer" id="answer{{ $correctAnswer->id }}" value="{{ $correctAnswer->id }}" disabled class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                                {!! $correctAnswer->answer !!}
                            </x-radio-btn>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-md mb-4 w-full md:w-8/12 mx-auto ">
                        <p class="w-full text-start text-sm mb-2">Explication :</p>
                        <p class="w-10/12 text-start cmu-serif clue-content break-words mb-4">{!! $correctAnswer->explanation !!}</p>
                    </div>
                    @if(session('currentQuestion') == count(session('questions')))
                    <div class="flex justify-center items-center w-full">
                    <x-button-result href="{{ route('show_result') }}">
                        Voir le résultat
                    </x-button-result>
                    </div>
                    @else
                    <x-button-next href="{{ route('show_question') }}">
                        Question suivante
                    </x-button-next>
                    @endif
                </div>
            </div>
            @endif
 
    </section>

@endsection