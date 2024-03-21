@extends('layouts.app')

@section('content')
<div id="loadingPopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
        <svg style="position: absolute; width: 0; height: 0;">
            <filter id="goo">
            <feGaussianBlur in="SourceGraphic" stdDeviation="12"></feGaussianBlur>
            <feColorMatrix values="0 0 0 0 0 
                      0 0 0 0 0 
                      0 0 0 0 0 
                      0 0 0 48 -7"></feColorMatrix>
          </filter>
          </svg>
          
          <div class="loader"></div>
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
        
        <form action="{{ route('exercise.update', $exercise->id) }}" method="POST"id="exerciseForm">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="name">Nom de l'Exercice (optionnel):</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom de l'exercice" value="{{ $exercise->name }}">
            </div>

            <div class="form-group">
                <label for="statement">Énoncé de l'Exercice (LaTeX):</label>
                <textarea class="form-control" id="statement" name="statement" rows="4" placeholder="Insérer le LaTeX ici...">{{ $exercise->statement }}</textarea>
            </div>

            <div class="form-group">
                <label for="solution">Solution de l'Exercice (LaTeX, optionnel):</label>
                <textarea class="form-control" id="solution" name="solution" rows="4" placeholder="Insérer le LaTeX ici...">{{ $exercise->solution }}</textarea>
            </div>

            <input type="hidden" name="subchapter_id" value="{{ $exercise->subchapter_id }}">

            <div class="form-group">
                <label for="clue">Indice pour l'Exercice (LaTeX, optionnel):</label>
                <textarea class="form-control" id="clue" name="clue" rows="3" placeholder="Insérer le LaTeX ici...">{{ $exercise->clue }}</textarea>
            </div>
            
            <button type="submit" class="submit-btn-form">Mettre à jour l'Exercice</button>
        </form>
    </div>
</section>
@endsection
