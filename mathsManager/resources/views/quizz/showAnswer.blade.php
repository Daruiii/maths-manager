@extends('layouts.app')

@section('content')
    <x-back-btn path="{{ route('end_quizz') }}">Quitter</x-back-btn>
    <x-progress-bar currentQuestion="{{ session('currentQuestion') }}" totalQuestions="{{ count(session('questions')) }}" />

    <section class="container mx-auto @if ($answer->is_correct) slide-left @endif mb-8">

        @if ($answer->is_correct)
            <div class="p-6 bg-white shadow-md rounded-md flex flex-col items-center justify-center mt-8">
                <h1 class="text-xl font-bold mb-4">Bravo, votre réponse est correcte !</h1> {{-- ici, on mettre image réussite --}}

                <p class="mb-4 text-green-500 text-2xl font-bold score-increase">+1</p>
                @if (session('currentQuestion') == count(session('questions')))
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
        <div class="text-center mt-4">
            <p class="text-sm">Question n°{{ session('currentQuestion') }}</p>
            {{-- <p class="text-xs"> {{ $question->subchapter->title }} </p> --}}
        </div>
        <div class="w-full mb-2 flex flex-col items-center justify-center md:p-4">
            <h1 class="text-center text-sm text-white clue-content cmu-serif px-4 py-8 bg-[#1d5945] border-4 border-[#664729] w-full md:w-1/2 break-words">{!! $question->question !!}</h1>
        </div>
                <section class="flex flex-col flex-wrap justify-center items-center">
                    @foreach ($answers as $answersAnswer)        
                        @if ($answersAnswer->id == $answer->id)
                        <x-radio-btn name="answer" id="answer{{ $answersAnswer->id }}" value="{{ $answersAnswer->id }}" my_answer disabled class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                            {!! $answersAnswer->answer !!}
                        </x-radio-btn>
                        @elseif ($answersAnswer->is_correct)
                        <x-radio-btn name="answer" id="answer{{ $answersAnswer->id }}" value="{{ $answersAnswer->id }}" correct_answer disabled class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                            {!! $answersAnswer->answer !!}
                        </x-radio-btn>
                        @else
                        <x-radio-btn name="answer" id="answer{{ $answersAnswer->id }}" value="{{ $answersAnswer->id }}" disabled class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                            {!! $answersAnswer->answer !!}
                        </x-radio-btn>
                        @endif
                    @endforeach
                </section>
            <div class="w-full mb-2 flex flex-col items-center justify-start md:p-4 rounded-md">
                <h1 class="text-center w-full md:w-1/2 text-xs mb-2">Explication</h1>
                <p class="text-center text-sm clue-content cmu-serif w-full md:w-1/2 break-word bg-gray-100 p-4">{!! $correctAnswer->explanation !!}</p>
            </div>
                <div class="my-2 flex justify-center items-center w-full">
                    @if (session('currentQuestion') == count(session('questions')))
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
        @endif

    </section>

@endsection
