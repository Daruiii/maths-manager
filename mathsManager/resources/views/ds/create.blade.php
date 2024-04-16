@extends('layouts.app')

@section('content')
    <div id="loadingPopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
            <svg style="left: 50%; top: 50%; position: absolute; transform: translate(-50%, -50%) matrix(1, 0, 0, 1, 0, 0);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 187.3 93.7" height="300px" width="400px">
                <path d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z" stroke-miterlimit="10" stroke-linejoin="round" stroke-linecap="round" stroke-width="4" fill="none" id="outline" stroke="#4E4FEB"></path>
                <path d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z" stroke-miterlimit="10" stroke-linejoin="round" stroke-linecap="round" stroke-width="4" stroke="#4E4FEB" fill="none" opacity="0.05" id="outline-bg"></path>
            </svg>
        </div>
    </div>

    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Générer un DS</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('ds.store') }}" method="POST" id="dsForm">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="type_bac">Type de bac :</label>
                    <input type="checkbox" id="type_bac" name="type_bac" value="1">
                </div>

                <div id="dsFormWrapper">
                <div class="form-group">
                    <label for="exercises_number">Nombre d'exercices :</label>
                    <select name="exercises_number" id="exercises_number">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4" selected>4</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="harder_exercises">Exercices plus difficiles :</label>
                    <input type="checkbox" id="harder_exercises" name="harder_exercises" value="1">
                </div>

                     {{-- chapitres associés --}}
                     <div class="form-group">
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
                                            <input type="checkbox" class="multiselect__checkbox" value="{{ $chapter->id }}" name="multiple_chapters[]">
                                            <span class="multiselect__label">{{ $chapter->title }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit">Générer le DS</button>
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
        document.getElementById('type_bac').addEventListener('change', function() {
            const exercises_number = document.getElementById('exercises_number');
            const harder_exercises = document.getElementById('harder_exercises');
            // for multiselect__filter
            const multiselectFilter = document.querySelector('.multiselect__filter');
            const chaptersCheckboxes = document.querySelectorAll('.multiselect__checkbox');

            if (this.checked) {
                dsFormWrapper.style.display = 'none';
                exercises_number.value = 4;
                harder_exercises.checked = false;
                exercises_number.setAttribute('hidden', 'hidden');
                harder_exercises.setAttribute('hidden', 'hidden');
                multiselectFilter.setAttribute('hidden', 'hidden');
                chaptersCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                    checkbox.setAttribute('hidden', 'hidden');
                });
            } else {
                dsFormWrapper.style.display = 'block';
                exercises_number.removeAttribute('hidden');
                harder_exercises.removeAttribute('hidden');
                multiselectFilter.removeAttribute('hidden');
                chaptersCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    checkbox.removeAttribute('hidden');
                });
            }
        });
    </script>
@endsection
