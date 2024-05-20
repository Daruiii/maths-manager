@extends('layouts.app')

@section('content')
<x-back-btn path="{{ route('end_quizz') }}">Quitter</x-back-btn>
<x-progress-bar currentQuestion="{{$currentQuestion + 1 }}" totalQuestions="{{ count($questions) }}" />
<section class="container mx-auto slide-left mb-8">
<div class="text-center mt-4">
    <p class="text-sm">Question n°{{ $currentQuestion + 1 }}</p>
    {{-- <p class="text-xs"> {{ $question->subchapter->title }} </p> --}}
</div>
    <div class="w-full mb-2 flex flex-col items-center justify-center md:p-4">
        <h1 class="text-center text-sm text-white clue-content cmu-serif px-4 py-8 bg-[#1d5945] border-4 border-[#664729] w-full md:w-1/2 break-words">{!! $question->question !!}</h1>
    </div>
        <form action="{{ route('check_answer') }}" method="POST" class="flex flex-col flex-wrap justify-center items-center">
            @csrf

            @foreach ($answers as $answer)

                <input type="hidden" name="correct_answer" value="{{ $correctAnswer }}">

                <x-radio-btn name="answer" id="answer{{ $answer->id }}" value="{{ $answer->id }}" required class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                    {!! $answer->answer !!}
                </x-radio-btn>
            @endforeach

            {{-- <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md mt-4 w-full">Confirmer</button> --}}
            <div class="mt-2 mb-2 flex justify-center items-center w-full p-4">
            <x-button-confirm />
            </div>
        </form>
</section>

@endsection