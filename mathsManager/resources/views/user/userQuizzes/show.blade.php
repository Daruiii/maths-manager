@extends('layouts.app')

@section('content')
    <x-back-btn path=""> Retour</x-back-btn>
    <div class="container mx-auto mb-8 mt-6">
        <div class="flex justify-center flex-row items-center mt-6 mb-4 w-full">
            <div class="flex flex-row justify-start items-center p-2 gap-2">
                @if (Str::startsWith($student->avatar, 'http'))
                    <img src="{{ $student->avatar }}"
                        class="w-9 h-9 rounded-full border border-black object-cover hover:brightness-50 transition duration-300  "alt="Profile Picture">
                @else
                    <img src="{{ asset('storage/images/' . $student->avatar) }}"
                        class="w-9 h-9 rounded-full border border-black object-cover hover:brightness-50 transition duration-300"
                        alt="Profile Picture">
                @endif
            </div>
            <h3>{{ $student->name }}</h3>
        </div>

    @if ($quizzes->count())
        <div class="w-full flex flex-wrap justify-center items-center gap-3">
            @foreach ($quizzes as $index => $quiz)
                <div class="quiz-card">
                    <div class="quiz-card-details">
                        <p class="text-title">Quizz n°{{ $index + 1 }}</p>
                        <p class="text-body">{{ $quiz->chapter->title }}</p>
                        <div class="quiz-card-score {{ $quiz->score < 5 ? 'score-low' : 'score-high' }}">
                            <p class="text-white">Score: {{ $quiz->score }}/10</p>
                        </div>
                    </div>
                    <button class="quiz-card-button"
                        onclick="window.location.href='{{ route('student.quizDetails', ['quiz_id' => $quiz->id]) }}'">Détails</button>
                </div>
            @endforeach
        </div>
    @else
    <div class="flex justify-center flex-col items-center w-full h-20 ">
        <h2 class="text-gray-500">Aucun quizz disponible</h2>
        <div class="flex justify-center items-center w-1/2">
            <p class="text-center text-gray-500 text-xs">Veuillez revenir plus tard</p>
        </div>
    </div>
    @endif
    </div>
    {{ $quizzes->links()}}
@endsection
