@extends('layouts.app')

@section('content')
    <div id="loadingPopup"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
            <svg style="left: 50%;
        top: 50%;
        position: absolute;
        transform: translate(-50%, -50%) matrix(1, 0, 0, 1, 0, 0);"
                preserveAspectRatio="xMidYMid meet" viewBox="0 0 187.3 93.7" height="300px" width="400px">
                <path
                    d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 				c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"
                    stroke-miterlimit="10" stroke-linejoin="round" stroke-linecap="round" stroke-width="4" fill="none"
                    id="outline" stroke="#4E4FEB"></path>
                <path
                    d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 				c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"
                    stroke-miterlimit="10" stroke-linejoin="round" stroke-linecap="round" stroke-width="4" stroke="#4E4FEB"
                    fill="none" opacity="0.05" id="outline-bg"></path>
            </svg>
        </div>
    </div>

    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Modifier l'Exercice</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('exercise.update', $exercise->id) }}" method="POST"id="exerciseForm" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="name">Nom de l'Exercice (optionnel):</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nom de l'exercice"
                        value="{{ $exercise->name }}">
                </div>

                <div class="form-group">
                    <x-multiple-file-input type="file" name="images_statement" id="images" label="Images de l'énoncé" />
                 </div>

                <div class="form-group">
                    <label for="statement">Énoncé de l'Exercice (LaTeX):</label>
                    <textarea class="form-control" id="statement" name="statement" rows="4" placeholder="Insérer le LaTeX ici...">{{ $exercise->latex_statement }}</textarea>
                </div>

                <div class="form-group">
                    <x-multiple-file-input type="file" name="images_solution" id="images" label="Images de la solution" />
                 </div>

                <div class="form-group">
                    <label for="solution">Solution de l'Exercice (LaTeX, optionnel):</label>
                    <textarea class="form-control" id="solution" name="solution" rows="4" placeholder="Insérer le LaTeX ici...">{{ $exercise->latex_solution }}</textarea>
                </div>

                <input type="hidden" name="subchapter_id" value="{{ $exercise->subchapter_id }}">

                <div class="form-group">
                    <label for="clue">Indice pour l'Exercice (LaTeX, optionnel):</label>
                    <textarea class="form-control" id="clue" name="clue" rows="3" placeholder="Insérer le LaTeX ici...">{{ $exercise->latex_clue }}</textarea>
                </div>

                <div class="form-group">
                    <label for="difficulty">Difficulté de l'Exercice :</label>
                    <select class="form-control" id="difficulty" name="difficulty">
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @if ($i == $exercise->difficulty) selected @endif>
                                {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="submit-btn-form">Mettre à jour l'Exercice</button>
            </form>
        </div>
    </section>
@endsection
