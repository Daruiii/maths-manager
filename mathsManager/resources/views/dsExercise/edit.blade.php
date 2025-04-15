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

            <form action="{{ route('ds_exercise.update', $dsExercise->id) }}" method="POST" id="dsExerciseForm"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- hidden input for give the filter true or false --}}
                <input type="hidden" name="filter" value="{{ $filter }}">
                <div class="form-group">
                    <label for="name">Nom de l'Exercice DS:</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $dsExercise->name }}"
                        placeholder="Nom de l'exercice DS">
                </div>

                <div class="form-group">
                    <label for="type">Type de l'Exercice DS:</label>
                    <select class="form-control" id="type" name="type">
                        <option value="bac" {{ $dsExercise->type == 'bac' ? 'selected' : '' }}>bac</option>
                        <option value="mimigl" {{ $dsExercise->type == 'mimigl' ? 'selected' : '' }}>mimigl</option>
                        <option value="lycee" {{ $dsExercise->type == 'lycee' ? 'selected' : '' }}>lycee</option>
                        <option value="concours" {{ $dsExercise->type == 'concours' ? 'selected' : '' }}>concours</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="year">Année</label>
                    <select class="form-control" id="year" name="year">
                        @for ($year = date('Y'); $year >= 1950; $year--)
                            <option value="{{ $year }}" {{ $dsExercise->year == $year ? 'selected' : '' }}>
                                {{ $year }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label for="academy">Académie:</label>
                    <select class="form-control" id="academy" name="academy">
                        <option value="" {{ $dsExercise->academy == '' ? 'selected' : '' }}>Aucun</option>
                        <option value="Metropole" {{ $dsExercise->academy == 'Metropole' ? 'selected' : '' }}>Metropole
                        </option>
                        <option value="Antille Guyane" {{ $dsExercise->academy == 'Antille Guyane' ? 'selected' : '' }}>
                            Antille Guyane</option>
                        <option value="Pondichéry" {{ $dsExercise->academy == 'Pondichéry' ? 'selected' : '' }}>Pondichéry
                        </option>
                        <option value="Polynésie" {{ $dsExercise->academy == 'Polynésie' ? 'selected' : '' }}>Polynésie
                        </option>
                        <option value="Asie" {{ $dsExercise->academy == 'Asie' ? 'selected' : '' }}>Asie</option>
                        <option value="Liban" {{ $dsExercise->academy == 'Liban' ? 'selected' : '' }}>Liban</option>
                        <option value="Nouvelle Calédonie"
                            {{ $dsExercise->academy == 'Nouvelle Calédonie' ? 'selected' : '' }}>Nouvelle Calédonie
                        </option>
                        <option value="Centres étrangers"
                            {{ $dsExercise->academy == 'Centres étrangers' ? 'selected' : '' }}>Centres étrangers</option>
                        <option value="Amérique du nord"
                            {{ $dsExercise->academy == 'Amérique du nord' ? 'selected' : '' }}>Amérique du nord</option>
                        <option value="Amérique du sud" {{ $dsExercise->academy == 'Amérique du sud' ? 'selected' : '' }}>
                            Amérique du sud</option>
                        <option value="La Réunion" {{ $dsExercise->academy == 'La Réunion' ? 'selected' : '' }}>La Réunion
                        </option>
                        <option value="Sujet 0" {{ $dsExercise->academy == 'Sujet 0' ? 'selected' : '' }}>Sujet 0</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Date de l'Exercice DS:</label>
                    <input type="text" class="form-control" id="date" name="date_data" value="{{ $dsExercise->date_data }}" placeholder="Exemple: mai 2022 s2">
                </div>

                <div class="form-group">
                    <x-multiple-file-input type="file" name="images" id="images"
                        imgFolder="storage/ds_exercises/ds_exercise_{{ $dsExercise->id }}" />
                </div>

                <div class="form-group">
                    <label for="statement">Énoncé de l'Exercice DS (LaTeX):</label>
                    <textarea class="form-control" id="statement" name="statement" rows="4" placeholder="Insérer le LaTeX ici...">{{ $dsExercise->latex_statement }}</textarea>
                </div>

                <div class="form-group">
                    <label for="correction_pdf">Correction (PDF) :</label>
                    <input type="file" class="form-control" id="correction_pdf" name="correction_pdf"
                        accept="application/pdf">
                    @if ($dsExercise->correction_pdf)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $dsExercise->correction_pdf) }}" target="_blank"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Voir le PDF actuel</a>
                            <input type="hidden" name="existing_correction_pdf" value="{{ $dsExercise->correction_pdf }}">
                            <div class="mt-2">
                                <label>
                                    <input type="checkbox" name="delete_correction_pdf" value="1">
                                    Supprimer le PDF actuel
                                </label>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="harder_exercise">Exercice plus difficile:</label>
                    <div class="flex items-center w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-black">
                        <input type="checkbox" id="harder_exercise" name="harder_exercise" value="1"
                            class="text-black border-gray-300 rounded focus:ring-blue-500 checked:bg-black"
                            {{ $dsExercise->harder_exercise ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="form-group">
                    <label for="time">Temps (en minutes):</label>
                    <input type="number" class="form-control" id="time" name="time"
                        value="{{ $dsExercise->time }}">
                </div>

                <div class="form-group">
                    <label for="multiple_chapter_id">Chapitre duo:</label>
                    <select class="form-control" id="multiple_chapter_id" name="multiple_chapter_id">
                        @foreach ($multipleChapters as $multipleChapter)
                            <option value="{{ $multipleChapter->id }}"
                                {{ $dsExercise->multiple_chapter_id == $multipleChapter->id ? 'selected' : '' }}>
                                {{ $multipleChapter->title }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- 
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
                </div> --}}

                <button type="submit" class="submit-btn-form">Modifier l'Exercice DS</button>
            </form>
        </div>
    </section>
    {{-- <script>
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
    </script> --}}
@endsection
