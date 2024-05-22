@extends('layouts.app')

@section('content')
<x-back-btn path="" > Retour</x-back-btn>
    <div class="container mx-auto mb-8">
        <div class="flex justify-between flex-row items-center mt-6 mb-4 w-full md:w-10/12">
            <div class="flex flex-row justify-start items-center p-2 gap-2">
                @if (Str::startsWith($quiz->student->avatar, 'http'))
                    <img src="{{ $quiz->student->avatar }}"
                        class="w-9 h-9 rounded-full border border-black object-cover hover:brightness-50 transition duration-300  "alt="Profile Picture">
                @else
                    <img src="{{ asset('storage/images/' . $quiz->student->avatar) }}"
                        class="w-9 h-9 rounded-full border border-black object-cover hover:brightness-50 transition duration-300"
                        alt="Profile Picture">
                @endif
                <div class="flex flex-col justify-center items-center">
                    <h3>{{ $quiz->student->name }}</h3>
                </div>
                <div class="flex flex-col items-center justify-center p-2 border rounded bg-white">
                    <h3>Score: {{ $quiz->score }} / 10</h3>
                </div>
            </div>
            <h3>Quizz {{ $quiz->chapter->title }}</h3>
        </div>

        @if ($quizDetails->count())
            <div class="bg-gray-100 shadow-md rounded w-full md:w-10/12 p-4 hover:shadow-lg hover:shadow-red-200 transition duration-300">
                @foreach ($quizDetails as $detail)
                    <div class="text-center mt-4">
                        <p class="text-sm">Question n°{{ $loop->iteration }}</p>
                    </div>
                    <div class="w-full mb-2 flex flex-col items-center justify-center md:p-4">
                        <h1
                            class="text-center text-sm text-white clue-content cmu-serif px-4 py-8 bg-[#1d5945] border-4 border-[#664729] w-full md:w-1/2 break-words">
                            {!! $detail->question->question !!}</h1>
                    </div>
                    <section class="flex flex-col flex-wrap justify-center items-center">
                        @if (
                            $detail->chosenAnswer &&
                                $detail->question->answers->where('is_correct', true)->pluck('id')->contains($detail->chosenAnswer->id))
                            <x-radio-btn name="answer" id="answer{{ $detail->chosenAnswer->id }}"
                                value="{{ $detail->chosenAnswer->id }}" correct_answer disabled
                                class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                                {!! $detail->chosenAnswer->answer !!}
                            </x-radio-btn>
                        @elseif($detail->chosenAnswer)
                            <x-radio-btn name="answer" id="answer{{ $detail->chosenAnswer->id }}"
                                value="{{ $detail->chosenAnswer->id }}" my_answer disabled
                                class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                                {!! $detail->chosenAnswer->answer !!}
                            </x-radio-btn>
                            @foreach ($detail->question->answers as $answer)
                                @if ($answer->is_correct)
                                    <x-radio-btn name="answer" id="answer{{ $answer->id }}" value="{{ $answer->id }}"
                                        correct_answer disabled class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                                        {!! $answer->answer !!}
                                    </x-radio-btn>
                                @break
                            @endif
                        @endforeach
                    @else
                        <x-radio-btn name="answer" id="noAnswer" value="noAnswer" my_answer disabled
                            class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                            Aucune réponse choisie
                        </x-radio-btn>
                        @foreach ($detail->question->answers as $answer)
                            @if ($answer->is_correct)
                                <x-radio-btn name="answer" id="answer{{ $answer->id }}" value="{{ $answer->id }}"
                                    correct_answer disabled class="w-full text-sm clue-content cmu-serif sm:w-1/2 p-2">
                                    {!! $answer->answer !!}
                                </x-radio-btn>
                            @break
                        @endif
                    @endforeach
                @endif
            </section>
            <hr class="w-1/2  my-4 mx-auto">
        @endforeach
    </div>
@else
    <p>No quiz details found.</p>
@endif
</div>
<x-button-back-top />
@endsection
