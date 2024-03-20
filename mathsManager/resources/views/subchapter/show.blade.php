@extends('layouts.app')

@section('content')
    <div class="container mt-5">
                <h1 class="uppercase">{{ $subchapter->name }}</h1>
                <p>{{ $subchapter->description }}</p>
                <div class="">
                    <a href="{{ route('exercise.create', ['id' => $subchapter->id]) }}" class="btn btn-primary">Ajouter un
                        exercice</a>
                </div>
                <div class="bg-green-100 p-5 flex flex-col align-start justify-start w-100">
                        @foreach ($exercises as $index => $ex)
                        <div class="bg-red-200">
                            <h1>Exercice {{ $index + 1 }} :
                                {{ $ex->name }}</h1>
                            <p>{{ $ex->clue }}</p>
                            <p>{{ $ex->solution }}</p>
                            <div class="pdf-container">
                                <img src="{{ asset('storage/latex_output/exercise_' . $ex->id . '/exercise_' . $ex->id . '.png') }}" alt="png" class="png" style="width: 600px; height: auto;">
                            </div>
                        </div>
                        @endforeach
                </div>
    </div>
    @endsection