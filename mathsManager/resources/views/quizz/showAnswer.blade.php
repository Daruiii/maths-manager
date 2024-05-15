@extends('layouts.app')

@section('content')

    <section class="flex items-center justify-center h-screen">
        <div class="p-6 bg-white shadow-md rounded-md">
            @if($answer->is_correct)
            <h1 class="text-xl font-bold mb-4 text-green-500">Félicitations, votre réponse est correcte !</h1>
            <p class="mb-4">Votre score est maintenant de {{ session('score') }}.</p>
            @else
            <h1 class="text-xl font-bold mb-4 text-red-500">Désolé, votre réponse est incorrecte.</h1>
            <p class="mb-4">Explication : {{ $explanation }}</p>
            @endif

            @if(session('currentQuestion') == count(session('questions')))
            <a href="{{ route('show_result') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md">Voir le score</a>
            @else
            <a href="{{ route('show_question') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md">Question suivante</a>
            @endif
        </div>
    </section>

@endsection