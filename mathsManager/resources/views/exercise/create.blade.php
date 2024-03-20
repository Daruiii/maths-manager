@extends('layouts.app')

@section('content')
<section class="form-wrapper">
    <div class="form">
        <h1 class="form-title">Ajouter un Exercice</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('exercise.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nom de l'Exercice (optionnel):</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom de l'exercice">
            </div>

            <div class="form-group">
                <label for="statement">Énoncé de l'Exercice (LaTeX):</label>
                <textarea class="form-control" id="statement" name="statement" rows="4" placeholder="Insérer le LaTeX ici..."></textarea>
            </div>

            <div class="form-group">
                <label for="solution">Solution de l'Exercice (LaTeX, optionnel):</label>
                <textarea class="form-control" id="solution" name="solution" rows="4" placeholder="Insérer le LaTeX ici..."></textarea>
            </div>

            <input type="hidden" name="subchapter_id" value="{{ $subchapter_id }}">

            <div class="form-group">
                <label for="clue">Indice pour l'Exercice (LaTeX, optionnel):</label>
                <textarea class="form-control" id="clue" name="clue" rows="3" placeholder="Insérer le LaTeX ici..."></textarea>
            </div>
            
            <button type="submit" class="submit-btn-form">Ajouter l'Exercice</button>
        </form>
    </div>
</section>
@endsection
