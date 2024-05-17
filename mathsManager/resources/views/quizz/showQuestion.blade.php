@extends('layouts.app')

@section('content')
<x-back-btn path="{{ route('end_quizz') }}">Quitter</x-back-btn>
<x-progress-bar currentQuestion="{{$currentQuestion + 1 }}" totalQuestions="{{ count($questions) }}" />
<section class="container mx-auto slide-left">
<div class="text-center mt-4">
    <p class="text-sm mb-4">Question n°{{ $currentQuestion + 1 }}</p>
</div>
    <div class="mt-4 flex flex-col items-center justify-center md:p-4">
        <h1 class="text-sm mb-4 clue-content cmu-serif bg-white p-4 rounded-md w-full md:w-7/12 break-words">{!! $question->question !!}</h1>
        <form action="{{ route('check_answer') }}" method="POST" class="mt-6 flex flex-wrap justify-center items-center gap-4 space-x-4">
            @csrf

            @foreach ($answers as $answer)

                <input type="hidden" name="correct_answer" value="{{ $correctAnswer }}">

                <x-radio-btn name="answer" id="answer{{ $answer->id }}" value="{{ $answer->id }}" required class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                    {!! $answer->answer !!}
                </x-radio-btn>
            @endforeach

            {{-- <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md mt-4 w-full">Confirmer</button> --}}
            <div class="mt-6 mb-2 flex justify-center items-center w-full p-4">
            <x-button-confirm />
            </div>
        </form>
    </div>
</section>

@endsection