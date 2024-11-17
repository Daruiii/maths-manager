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

            <form action="{{ route('exercise.store') }}" method="POST" id="exerciseForm" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Nom de l'Exercice (optionnel):</label>
                    <input type="text" class="form-control" id="name" name="name"
                        placeholder="Nom de l'exercice">
                </div>

                <div class="form-group">
                    <x-multiple-file-input type="file" name="images_statement" id="images" label="Images de l'énoncé" />
                 </div>

                <div class="form-group">
                    <label for="statement">Énoncé de l'Exercice (LaTeX):</label>
                    <textarea class="form-control" id="statement" name="statement" rows="4" placeholder="Insérer le LaTeX ici..."></textarea>
                </div>

                <div class="form-group">
                    <x-multiple-file-input type="file" name="images_solution" id="images" label="Images de la solution" />
                 </div>

                <div class="form-group">
                    <label for="solution">Solution de l'Exercice (LaTeX, optionnel):</label>
                    <textarea class="form-control" id="solution" name="solution" rows="4" placeholder="Insérer le LaTeX ici..."></textarea>
                </div>

                @if ($subchapter_id)
                    <input type="hidden" name="subchapter_id" value="{{ $subchapter_id }}">
                @else
                    <div class="form-group">
                        <label for="subchapter_id">Sous-chapitre de l'Exercice:</label>
                        <select class="form-control" id="subchapter_id" name="subchapter_id">
                            @foreach ($subchapters as $subchapter)
                                <option value="{{ $subchapter->id }}">{{ $subchapter->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="form-group">
                    <label for="clue">Indice pour l'Exercice (LaTeX, optionnel):</label>
                    <textarea class="form-control" id="clue" name="clue" rows="3" placeholder="Insérer le LaTeX ici..."></textarea>
                </div>

                <div class="form-group">
                    <label for="difficulty">Difficulté de l'Exercice :</label>
                    <select class="form-control" id="difficulty" name="difficulty">
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="submit-btn-form">Ajouter l'Exercice</button>
            </form>
        </div>
    </section>
@endsection
