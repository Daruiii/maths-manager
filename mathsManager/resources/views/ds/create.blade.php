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

    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Générer un devoir</h1>
            <p class="form-explain mb-5"> Ce générateur de DS vous permet de vous tester dans des conditions réelles
                d'examens. Vous avez un temps limité qui s'affichera alors soyez sur d'être prêt avant de commencer le
                devoir. Aucune aide ne sera donnée, vous pouvez faire pause et revenir sur le DS plus tard. Lorsque vous
                terminez le DS, vous pouvez prendre en photo votre travail et me l'envoyer pour obtenir une note et une
                correction de la copie.
                Vous ne pouvez pas générer plusieurs DS en même temps, vous devrez me demander pour pouvoir générer à
                nouveau </p>

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
                    <div class="flex items-center gap-1">
                        <label>Type bac :</label>
                        <input type="checkbox" id="type_bac" name="type_bac" value="1">
                    </div>
                    <p class="form-explain">En cochant cette case, une simulation du bac aléatoire de 4 exercices se génère
                        automatiquement, vous ne pouvez donc pas choisir les chapitres.</p>
                </div>

                <div id="dsFormWrapper">

                    <div class="form-group">
                        <div class="flex items-center gap-1">
                            <label for="harder_exercises">Mode difficile :</label>
                            <input type="checkbox" id="harder_exercises" name="harder_exercises" value="1">
                        </div>
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

                    {{-- chapitres associés --}}
                    <div class="form-group">
                        <label for="chapters">Sélectionner des chapitres :</label>
                        <div class="multiselect">
                            <div class="multiselect__selected" tabindex="0">
                                <i class="fas fa-caret-down"></i>
                            </div>
                            <div class="multiselect__dropdown">
                                <input type="text" class="multiselect__filter" placeholder="Rechercher...">
                                <div class="multiselect__options" style="max-height: 500px; overflow-y: auto;">
                                    {{-- input tout selectionner --}}
                                    <input type="checkbox" class="multiselect__checkbox" value="all" id="selectAll"
                                        name="all">
                                    <span class="multiselect__label comfortaa-light text-sm" id="selectAll">Tout
                                        sélectionner</span>
                                    @foreach ($chapters as $chapter)
                                        <div class="multiselect__option m-0">
                                            <input type="checkbox" class="multiselect__checkbox" value="{{ $chapter->id }}"
                                                name="multiple_chapters[]">
                                            <span
                                                class="multiselect__label comfortaa-light text-sm">{{ $chapter->title }}</span>
                                        </div>
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
            const chaptersCheckboxes = document.querySelectorAll('.multiselect__checkbox');
            const selectAll = document.getElementById('selectAll');
            // if select all is checked, select all checkboxes
            document.getElementById('selectAll').addEventListener('change', function() {
                if (this.checked) {
                    chaptersCheckboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                } else {
                    chaptersCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
            });
            document.getElementById('type_bac').addEventListener('change', function() {
                const dsFormWrapper = document.getElementById('dsFormWrapper');
                if (this.checked) {
                    chaptersCheckboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                    exercises_number.value = 4;
                    harder_exercises.checked = false;
                    dsFormWrapper.style.display = 'none';
                } else {
                    chaptersCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    dsFormWrapper.style.display = 'block';
                }
            });
        });
    </script>
@endsection
