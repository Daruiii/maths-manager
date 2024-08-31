@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
    <x-back-btn path="">Retour</x-back-btn>
        <div class="flex flex-col align-center items-center justify-center my-5 bg-white w-full md:w-4/5 rounded-lg box-shadow shadow-xl">
            <div class="flex flex-col items-center justify-center mt-24 mb-16 gap-8 w-3/4" id="entete">
                <div class="flex flex-col items-center justify-center">
                    <h1 class="text-lg text-center cmu-serif uppercase">F<span class="text-sm">iche d'exercices</span></h1>
                    <h1 class="text-lg text-center cmu-serif uppercase">M<span class="text-sm">athématiques</span></h1>
                    <h1 class="text-lg text-center cmu-serif uppercase">T<span class="text-sm">erminale</span> S<span class="text-sm">pécialité</span></h1>
                </div>
            </div>
            <div class="w-9/12 flex flex-col items-start justify-start">
                @foreach ($exercisesSheet->exercises as $index => $exercise)
                    <div class="mb-16 w-full" id="exercise-{{ $index + 1 }}">
                        <div class="exercise-content text-sm cmu-serif min-w-full">
                            <span class="truncate font-bold text-sm exercise-title">(#{{$exercise->order}})Exercice {{ $index + 1 }}. </span>
                            {!! $exercise->statement !!}
                        </div>
                    </div>
                @endforeach
            </div>
            <x-button-back-top />
        </div>
    </div>
@endsection
