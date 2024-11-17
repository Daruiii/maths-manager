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
            <h1 class="form-title">Ajouter un Exercice DS</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('ds_exercise.store') }}" method="POST" id="dsExerciseForm" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Nom de l'Exercice DS:</label>
                    <input type="text" class="form-control" id="name" name="name"
                        placeholder="Nom de l'exercice DS">
                </div>

                <div class="form-group">
                   <x-multiple-file-input type="file" name="images" id="images" />
                </div>

                <div class="form-group">
                    <label for="statement">Énoncé de l'Exercice DS (LaTeX):</label>
                    <textarea class="form-control" id="statement" name="statement" rows="4" placeholder="Insérer le LaTeX ici..."></textarea>
                </div>

                <div class="form-group">
                    <label for="harder_exercise">Exercice plus difficile:</label>
                    <div class="flex items-center w-4 h-4 border-gray-300 rounded focus:ring-blue-500 text-black">
                        <input type="checkbox" id="harder_exercise" name="harder_exercise" value="1"
                            class="text-black border-gray-300 rounded focus:ring-blue-500 checked:bg-black">
                    </div>
                </div>


                <div class="form-group">
                    <label for="time">Temps (en minutes):</label>
                    <input type="number" class="form-control" id="time" name="time" value="30">
                </div>

                <div class="form-group">
                    <label for="multiple_chapter_id">Chapitre duo:</label>
                    <select class="form-control" id="multiple_chapter_id" name="multiple_chapter_id">
                        @foreach ($multipleChapters as $multipleChapter)
                            <option value="{{ $multipleChapter->id }}">{{ $multipleChapter->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- chapitres associés --}}
                {{-- <div class="form-group">
                    <label for="chapters">Chapitres associés:</label>
                    <div class="multiselect">
                        <div class="multiselect__selected" tabindex="0">
                            <span>Sélectionner des chapitres</span>
                            <i class="fas fa-caret-down"></i>
                        </div>
                        <div class="multiselect__dropdown">
                            <input type="text" class="multiselect__filter" placeholder="Rechercher...">
                            <div class="multiselect__options" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($chapters as $chapter)
                                    <label class="multiselect__option">
                                        <input type="checkbox" class="multiselect__checkbox" value="{{ $chapter->id }}" name="chapters[]">
                                        <span class="multiselect__label">{{ $chapter->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div> --}}
                <button type="submit" class="submit-btn-form">Ajouter l'Exercice DS</button>
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
