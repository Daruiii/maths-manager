@extends('layouts.app')

@section('content')
<section class="container mx-auto">
<x-back-btn path="{{ route('classe.show', $question->chapter->classe->level) }}" />
<div class="text-center mt-4">
    Question {{ $currentQuestion + 1 }}/{{ count($questions )}}
    Score : {{ $score }}/ {{ count($questions )}}
</div>
    <div class="p-6 bg-white shadow-md rounded-md">
        <h1 class="text-xl font-bold mb-4">{{ $question->question }}</h1>

        <form action="{{ route('check_answer') }}" method="POST">
            @csrf

            @foreach ($answers as $answer)
                <div class="p-4 bg-white rounded-md mb-2">

                    <input type="hidden" name="correct_answer" value="{{ $correctAnswer }}">

                    <input class="form-check-input" type="radio" name="answer" id="answer{{ $answer->id }}" value="{{ $answer->id }}">
                    <label class="ml-2" for="answer{{ $answer->id }}">
                        {{ $answer->answer }}
                    </label>
                </div>
            @endforeach

            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Soumettre la réponse</button>
        </form>
    </div>
</section>

@endsection