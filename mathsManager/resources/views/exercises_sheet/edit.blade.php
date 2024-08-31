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
            <h1 class="form-title">Modifier une fiche d'exercices</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('exercises_sheet.update', $exercisesSheet->id) }}" method="POST" id="exercisesSheetForm">
                @csrf
                @method('PATCH')
                
                <input type="hidden" name="chapter_id" value="{{ $exercisesSheet->chapter_id }}">
                
                <div class="form-group">
                    <label for="user_id">Étudiant :</label>
                    <select id="user_id" name="user_id" style="width: 100%;">
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ $student->id == $exercisesSheet->user_id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
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
                                @foreach ($exercises as $exercise)
                                    <div class="multiselect__option m-0">
                                        <input type="checkbox" class="multiselect__checkbox" value="{{ $exercise->id }}"
                                            name="exercises[]" {{ in_array($exercise->id, $exercisesSheet->exercises->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <span class="multiselect__label comfortaa-light text-sm p-2 rounded">
                                            {{ $exercise->name }} #{{ $exercise->id }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit">Mettre à jour la fiche d'exercices</button>
            </form>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            $('#user_id').select2();
        });

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
    </script>
@endsection
