@extends('layouts.app')

@section('content')
    <div id="loadingPopup"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
            <svg style="left: 50%; top: 50%; position: absolute; transform: translate(-50%, -50%) matrix(1, 0, 0, 1, 0, 0);"
                preserveAspectRatio="xMidYMid meet" viewBox="0 0 187.3 93.7" height="300px" width="400px">
                <path
                    d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"
                    stroke-miterlimit="10" stroke-linejoin="round" stroke-linecap="round" stroke-width="4" fill="none"
                    id="outline" stroke="#4E4FEB"></path>
                <path
                    d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"
                    stroke-miterlimit="10" stroke-linejoin="round" stroke-linecap="round" stroke-width="4" stroke="#4E4FEB"
                    fill="none" opacity="0.05" id="outline-bg"></path>
            </svg>
        </div>
    </div>
    <x-back-btn path="">Retour</x-back-btn>
    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Assigner un devoir</h1>
            <p class="form-explain mb-5"> Ici, vous pouvez assigner un devoir à un utilisateur spécifique. </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('ds.assign') }}" method="POST" id="dsForm">
                @csrf
                <div class="form-group">
                    <label>User :</label>
                    <select id="user" name="user_id">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <div class="flex items-center gap-1">
                        <label>Type bac :</label>
                        <input type="checkbox" id="type_bac" name="type_bac" value="1">
                    </div>
                    <p class="form-explain">En cochant cette case, une simulation du bac aléatoire de 4 exercices se génère
                        automatiquement, vous ne pouvez donc pas choisir les chapitres.</p>
                </div>

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
                    <div class="flex items-center gap-1">
                        <label for="harder_exercises">Mode difficile :</label>
                        <input type="checkbox" id="harder_exercises" name="harder_exercises" value="1">
                    </div>
                </div>

                <div class="form-group">
                    <label for="exercises">Sélectionner des exercices :</label>
                    <div class="multiselect">
                        <div class="multiselect__selected" tabindex="0">
                            <i class="fas fa-caret-down"></i>
                        </div>
                        <div class="multiselect__dropdown">
                            <input type="text" id="search" class="multiselect__filter" placeholder="Rechercher...">
                            <div class="multiselect__options" style="max-height: 500px; overflow-y: auto;">
                                {{-- input tout selectionner --}}
                                <input type="checkbox" class="multiselect__checkbox" value="all" id="selectAllExercises"
                                    name="all">
                                <span class="multiselect__label comfortaa-light text-sm" id="selectAllExercises">Tout
                                    sélectionner</span>
                                @foreach ($exercises as $exercise)
                                    <div class="multiselect__option m-0">
                                        <input type="checkbox" class="multiselect__checkbox" value="{{ $exercise->id }}"
                                            name="exercisesDS[]">
                                        <span class="multiselect__label comfortaa-light text-sm"
                                            style="background-color: {{ $exercise->multipleChapter->theme }};">{{ $exercise->name }}
                                            #{{ $exercise->id }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit">Générer et assigner le DS</button>
            </form>

        </div>
    </section>
    <script>
        document.getElementById('search').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();

            const options = document.querySelectorAll('.multiselect__option');

            options.forEach(option => {
                const label = option.querySelector('.multiselect__label').textContent.toLowerCase();

                if (label.includes(searchValue)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const exercisesCheckboxes = document.querySelectorAll('.multiselect__checkbox');
            const selectAllExercises = document.getElementById('selectAllExercises');

            // if select all is checked, select all checkboxes
            document.getElementById('selectAllExercises').addEventListener('change', function() {
                if (this.checked) {
                    exercisesCheckboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                } else {
                    exercisesCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
            });
            document.getElementById('type_bac').addEventListener('change', function() {
                const dsFormWrapper = document.getElementById('dsFormWrapper');
                if (this.checked) {
                    exercisesCheckboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                    exercises_number.value = 4;
                    harder_exercises.checked = false;
                    dsFormWrapper.style.display = 'none';
                } else {
                    exercisesCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    dsFormWrapper.style.display = 'block';
                }
            });
        });
    </script>
@endsection
