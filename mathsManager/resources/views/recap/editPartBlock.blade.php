@extends('layouts.app')

@section('content')

    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Modifier un Bloc</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('recapPartBlock.update', $recapPartBlock->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="title">Titre du bloc :</label>
                    <input type="text" class="form-control" id="title" name="title" required
                        placeholder="Nom de l'exercice" value="{{ $recapPartBlock->title }}">
                </div>

                {{-- récap_part_id --}}
                <input type="hidden" name="recap_part_id" value="{{ $recapPartBlock->recap_part_id }}">

                {{-- color picker for theme --}}
                <div class="form-group">
                    <label for="theme" class="block text-sm font-medium mb-2 dark:text-white">Thème du bloc :</label>
                    <select class="form-control text-white font-bold" id="theme" name="theme" onchange="changeBackground(this)" style="background-color: {{ $recapPartBlock->theme }};">
                        <option value="">Sélectionner un thème</option>
                        <option value="Théorèmes" {{ $recapPartBlock->theme == 'Théorèmes' ? 'selected' : '' }}>Théorèmes</option>
                        <option value="Définitions" {{ $recapPartBlock->theme == 'Définitions' ? 'selected' : '' }}>Définitions</option>
                        <option value="Lemme" {{ $recapPartBlock->theme == 'Lemme' ? 'selected' : '' }}>Lemme</option>
                        <option value="Remarque" {{ $recapPartBlock->theme == 'Remarque' ? 'selected' : '' }}>Remarque</option>
                    </select>
                    <script>
                        onload = function() {
                            changeBackground(document.getElementById('theme'));
                        }
                        function changeBackground(select) {
                            var selectedOption = select.options[select.selectedIndex];
                            if (selectedOption.value === 'Théorèmes') {
                                document.getElementById('theme').style.backgroundColor = '#E35F53';
                            } else if (selectedOption.value === 'Définitions') {
                                document.getElementById('theme').style.backgroundColor = '#4896ac';
                            } else if (selectedOption.value === 'Lemme') {
                                document.getElementById('theme').style.backgroundColor = '#65a986';
                            } else if (selectedOption.value === 'Remarque') {
                                document.getElementById('theme').style.backgroundColor = '#bababa';
                            } else {
                                document.getElementById('theme').style.backgroundColor = 'white';
                            }
                        }
                    </script>
                </div>

                <div class="form-group">
                    <label for="content">Contenu du bloc (LaTeX):</label>
                    <textarea class="form-control" id="content" name="content" rows="4" placeholder="Insérer le LaTeX ici...">{{ $recapPartBlock->latex_content }}</textarea>
                </div>

                <div class="form-group">
                    <label for="example">Exemple (LaTeX/optionnel) :</label>
                    <textarea class="form-control" id="clue" name="example" rows="4" placeholder="Insérer le LaTeX ici...">{{ $recapPartBlock->latex_example }}</textarea>
                </div>

                <button type="submit" class="submit-btn-form">Modifier le bloc</button>
            </form>
        </div>
    </section>
@endsection
