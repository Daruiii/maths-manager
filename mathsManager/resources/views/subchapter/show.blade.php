@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="uppercase">{{ $subchapter->name }}</h1>
        <p>{{ $subchapter->description }}</p>
        <div class="">
            <a href="{{ route('exercise.create', ['id' => $subchapter->id]) }}" class="btn btn-primary">Ajouter un exercice</a>
        </div>
        <div class="bg-green-100 p-5 flex flex-col align-start justify-start w-100">
            @foreach ($exercises as $index => $ex)
                @php
                    $exerciseId = $ex->id;
                    $exerciseFiles = Storage::disk('public')->files("latex_output/exercise_{$exerciseId}/exercise");
                    $exercisePngFiles = array_filter($exerciseFiles, function ($file) {
                        return Str::endsWith($file, '.png');
                    });
                    $solutionFiles = Storage::disk('public')->files("latex_output/exercise_{$exerciseId}/correction");
                    $solutionPngFiles = array_filter($solutionFiles, function ($file) {
                        return Str::endsWith($file, '.png');
                    });
                @endphp
                <div class="bg-red-200 mb-4">
                    <h2>Exercice {{ $index + 1 }}: {{ $ex->name }}</h2>
                    @foreach ($exercisePngFiles as $pngFile)
                        <div class="pdf-container">
                            <img src="{{ asset('storage/' . $pngFile) }}" alt="Exercice image" class="png" style="width: 500px; height: auto;">
                        </div>
                    @endforeach
                    @if ($ex->solution)
                    <h3>Correction:</h3>
                    @foreach ($solutionPngFiles as $pngFile)
                        <div class="pdf-container">
                            <img src="{{ asset('storage/' . $pngFile) }}" alt="Solution image" class="png" style="width: 500px; height: auto;">
                        </div>
                    @endforeach
                    @endif
                </div>
                <div>
                <form action="{{ route('exercise.destroy', $ex->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice ?');">
                        Supprimer
                    </button>
                </form>
                <form action="{{ route('exercise.edit', ['id' => $ex->id]) }}" method="GET" class="inline">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Modifier
                    </button>
                </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
