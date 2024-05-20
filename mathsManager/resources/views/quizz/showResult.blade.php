@extends('layouts.app')

@section('content')
<x-back-btn path="{{ route('end_quizz') }}">Quitter</x-back-btn>

    <section class="container mx-auto slide-left mb-8">
        <div class="bg-white shadow-md rounded-md flex flex-col items-center justify-center mt-8 relative px-12 py-4">
            @if ($score >= 5)
            <p class="text-sm text-center mb-4">Félicitations pour avoir terminé le quizz : <br><strong> {{ $chapter->title }}</strong> !</p> {{-- image de fin de quizz positif --}}
            <img src="{{ asset('storage/images/quizzGood.png') }}" alt="image de fin de quizz positif" width="200px" height="200px" class="absolute z-10" style="bottom: 63.30%; transform: translateY(50%);">
            <div class="flex flex-col items-center justify-center bg-emerald-950 border-4 border-amber-950 w-80 relative z-0 py-14 mt-32 mb-2">
                <p class="w-full text-center text-2xl text-white chalkabout">{{ $score }} / {{ $totalQuestions }}</p>
            </div>
            <p class="text-xs text-center my-2">Continue à t'entraîner pour renforcer tes <br>compétences et atteindre tes objectifs !</p>
            @else
            <p class="text-sm text-center mb-4">Félicitations pour avoir terminé le quizz : <br><strong> {{ $chapter->title }}</strong> !</p> 
            <img src="{{ asset('storage/images/quizzBad.png') }}" alt="image de fin de quizz négatif" width="200px" height="200px" class="absolute z-10" style="bottom: 67%; transform: translateY(50%);">
            <div class="flex flex-col items-center justify-center bg-emerald-950 border-4 border-amber-950 w-80 relative z-0 py-14 mt-24 mb-2">
                <p class="w-full text-center text-2xl text-white chalkabout">{{ $score }} / {{ $totalQuestions }}</p>
            </div>
            <p class="text-xs text-center my-2">Continue à t'entraîner pour renforcer tes <br>compétences et atteindre tes objectifs !</p>
            @endif
            <x-button-next href="{{ route('start_quizz', $chapter->id) }}">
                Recommencer un quizz
            </x-button-next>
        </div>
    </section>

@endsection