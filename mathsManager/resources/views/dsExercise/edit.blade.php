@extends('layouts.app')

@section('content')
    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Modifier un Exercice DS</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('ds_exercise.update', $dsExercise->id) }}" method="POST" id="dsExerciseForm">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="name">Nom de l'Exercice DS:</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $dsExercise->name }}" placeholder="Nom de l'exercice DS">
                </div>

                <div class="form-group">
                    <label for="statement">Énoncé de l'Exercice DS (LaTeX):</label>
                    <textarea class="form-control" id="statement" name="statement" rows="4" placeholder="Insérer le LaTeX ici...">{{ $dsExercise->latex_statement }}</textarea>
                </div>

                <div class="form-group">
                    <label for="harder_exercise">Exercice plus difficile:</label>
                    <div class="flex items-center w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-black">
                        <input type="checkbox" id="harder_exercise" name="harder_exercise" value="1" class="text-black border-gray-300 rounded focus:ring-blue-500 checked:bg-black" {{ $dsExercise->harder_exercise ? 'checked' : '' }}>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="time">Temps (en minutes):</label>
                    <input type="number" class="form-control" id="time" name="time" value="{{ $dsExercise->time }}">
                </div>

                <div class="form-group">
                    <label for="multiple_chapter_id">Chapitre:</label>
                    <select class="form-control" id="multiple_chapter_id" name="multiple_chapter_id">
                        @foreach ($multipleChapters as $multipleChapter)
                            <option value="{{ $multipleChapter->id }}" {{ $dsExercise->multiple_chapter_id == $multipleChapter->id ? 'selected' : '' }}>{{ $multipleChapter->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="chapters">Chapitres associés:</label>
                    <div class="multiselect">
                        <input type="text" class="multiselect__filter" placeholder="Rechercher...">
                        <div class="multiselect__options" style="max-height: 200px; overflow-y: auto;">
                            @foreach ($chapters as $chapter)
                                <label class="multiselect__option">
                                    <input type="checkbox" class="multiselect__checkbox" name="chapters[]" value="{{ $chapter->id }}" {{ $dsExercise->chapters->contains($chapter->id) ? 'checked' : '' }}>
                                    <span class="multiselect__label">{{ $chapter->title }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn-form">Modifier l'Exercice DS</button>
            </form>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterInput = document.querySelector('.multiselect__filter');
            filterInput.addEventListener('input', function() {
                const filterValue = this.value.toLowerCase();
                const options = document.querySelectorAll('.multiselect__option');
                options.forEach(function(option) {
                    const label = option.querySelector('.multiselect__label').innerText
                    .toLowerCase();
                    if (label.includes(filterValue)) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
