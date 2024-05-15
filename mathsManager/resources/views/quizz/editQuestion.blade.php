@extends('layouts.app')

@section('content')

    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Modifier une Question</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('quizz.update', $question->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="filter" value="{{ $filter }}">
                <div class="form-group">
                    <label for="question">Question :</label>
                    <textarea class="form-control" id="question" name="question" required placeholder="Insérer le LaTeX ici...">{{ $question->latex_question }}</textarea>
                </div>

                <div class="form-group">
                    <label for="chapter_id">Chapitre lié :</label>
                    <select class="form-control" id="chapter_id" name="chapter_id" required>
                        @foreach ($chapters as $chapter)
                            <option value="{{ $chapter->id }}" {{ $chapter->id == $question->chapter_id ? 'selected' : '' }}>{{ $chapter->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="subchapter_id">Sous-chapitre lié (optionnel) :</label>
                    <select class="form-control" id="subchapter_id" name="subchapter_id">
                        @foreach ($subchapters as $subchapter)
                            <option value="{{ $subchapter->id }}" {{ $subchapter->id == $question->subchapter_id ? 'selected' : '' }}>{{ $subchapter->title }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="submit-btn-form">Mettre à jour la question</button>
            </form>
        </div>
    </section>
    <script>
        // Convertir les sous-chapitres PHP en un tableau JavaScript
        var subchapters = @json($subchapters);
    
        var chapterSelect = document.getElementById('chapter_id');
        var subchapterSelect = document.getElementById('subchapter_id');
    
        function updateSubchapters() {
            var selectedChapterId = chapterSelect.value;
    
            // Effacer les options existantes
            subchapterSelect.innerHTML = '';
    
            // Ajouter une option par défaut
            var defaultOption = document.createElement('option');
            defaultOption.text = 'Sélectionner un sous-chapitre';
            defaultOption.value = '';
            subchapterSelect.add(defaultOption);
    
            // Filtrer les sous-chapitres et ajouter les options correspondantes
            subchapters.forEach(function(subchapter) {
                if (subchapter.chapter_id == selectedChapterId) {
                    var option = document.createElement('option');
                    option.text = subchapter.title;
                    option.value = subchapter.id;
                    subchapterSelect.add(option);
                }
            });
        }
    
        // Mettre à jour les sous-chapitres lorsque le chapitre sélectionné change
        chapterSelect.addEventListener('change', updateSubchapters);
    
        // Mettre à jour les sous-chapitres lorsque la page est chargée
        window.addEventListener('load', function() {
            updateSubchapters();
            subchapterSelect.value = '{{ $question->subchapter_id }}';
        });
    </script>
@endsection