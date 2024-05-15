@extends('layouts.app')

@section('content')

    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Modifier une Réponse</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('quizz.answer.update', $answer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="filter" value="{{ $filter }}">

                <div class="form-group">
                    <label for="answer">Réponse :</label>
                    <textarea class="form-control" id="answer" name="answer" required placeholder="Insérer le LaTeX ici...">{{ $answer->latex_answer }}</textarea>
                </div>

                <div class="form-group">
                    <label for="explanation">Explication :</label>
                    <textarea class="form-control" id="explanation" name="explanation" placeholder="Insérer le LaTeX ici...">{{ $answer->latex_explanation }}</textarea>
                </div>

                <div class="form-group">
                    <label for="is_correct">Est-ce correct ? :</label>
                    <select class="form-control" id="is_correct" name="is_correct" required>
                        <option value="">Sélectionner...</option>
                        <option value="1" {{ $answer->is_correct == 1 ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ $answer->is_correct == 0 ? 'selected' : '' }}>Non</option>
                    </select>
                </div>

                <button type="submit" class="submit-btn-form">Mettre à jour la réponse</button>
            </form>
        </div>
    </section>

@endsection