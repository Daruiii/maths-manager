@extends('layouts.app')

@section('content')
    <h1>DS de {{ auth()->user()->name }}</h1>

    @foreach ($dsList as $ds)
        <div class="ds">
            <p>{{ $ds->exercises_number }} exercices</p>
            <p>{{ $ds->harder_exercises ? 'Exercices plus difficiles' : '' }}</p>
            <p>{{ $ds->type_bac ? 'Type de bac' : '' }}</p>
            <p>Chapitres associés :</p>
            <ul>
                @foreach ($ds->chapters as $chapter)
                    <li>{{ $chapter->title }}</li>
                @endforeach
            </ul>
            <a href="{{ route('ds.edit', $ds->id) }}">Modifier</a>
            <form action="{{ route('ds.destroy', $ds->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Supprimer</button>
            </form>
        </div>
    @endforeach
@endsection
