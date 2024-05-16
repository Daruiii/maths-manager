@extends('layouts.app')

@section('content')
<x-back-btn path="{{ route('end_quizz') }}">Quitter</x-back-btn>
<x-progress-bar currentQuestion="{{$currentQuestion + 1 }}" totalQuestions="{{ count($questions) }}" />
<section class="container mx-auto slide-left">
<div class="text-center mt-4">
    Score : {{ $score }}/ {{ count($questions )}}
</div>
    <div class="p-6 shadow-md rounded-md">
        <h1 class="text-xl font-bold mb-4 clue-content cmu-serif bg-white p-4 rounded-md">{!! $question->question !!}</h1>

        <form action="{{ route('check_answer') }}" method="POST" class="flex flex-wrap justify-between">
            @csrf

            @foreach ($answers as $answer)

                <input type="hidden" name="correct_answer" value="{{ $correctAnswer }}">

                <x-radio-btn name="answer" id="answer{{ $answer->id }}" value="{{ $answer->id }}" required class="w-full sm:w-1/2 p-2">
                    {!! $answer->answer !!}
                </x-radio-btn>
            @endforeach

            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md mt-4 w-full">Confirmer</button>
        </form>
    </div>
</section>

@endsection