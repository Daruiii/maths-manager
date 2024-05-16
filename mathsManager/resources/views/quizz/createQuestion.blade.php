@extends('layouts.app')

@section('content')

    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Ajouter une Question</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('quizz.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="question">Question :</label>
                    <textarea class="form-control" id="question" name="question" placeholder="Insérer le LaTeX ici..."></textarea>
                </div>

                <div class="form-group">
                    <label for="chapter_id">Chapitre lié :</label>
                    <select class="form-control" id="chapter_id" name="chapter_id" required>
                        <option value="">Sélectionner un chapitre</option>
                        @foreach ($chapters as $chapter)
                            <option value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="subchapter_id">Sous-chapitre lié (optionnel) :</label>
                    <select class="form-control" id="subchapter_id" name="subchapter_id">
                        <option value="">Sélectionner un sous-chapitre</option>
                        @foreach ($subchapters as $subchapter)
                            <option value="{{ $subchapter->id }}">{{ $subchapter->title }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="submit-btn-form">Ajouter la question</button>
            </form>
        </div>
    </section>
    <script>
        // Convertir les sous-chapitres PHP en un tableau JavaScript
        var subchapters = @json($subchapters);
    
        document.getElementById('chapter_id').addEventListener('change', function() {
            var selectedChapterId = this.value;
            var subchapterSelect = document.getElementById('subchapter_id');
    
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
        });
    </script>
@endsection