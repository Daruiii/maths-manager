@extends('layouts.app')

@section('content')

    <section class="flex items-center justify-center h-screen">
        <x-back-btn path="{{ route('end_quizz') }}" />
        <div class="p-6 bg-white shadow-md rounded-md">
            <h1 class="text-xl font-bold mb-4">Résultat du quizz</h1>
            <p class="mb-4">Vous avez obtenu un score de {{ $score }} sur {{ $totalQuestions }}.</p>
            <a href="{{ route('start_quizz', $chapter->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md">Recommencer le quizz</a>
        </div>
    </section>

@endsection