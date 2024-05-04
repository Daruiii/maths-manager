@extends('layouts.app')

@section('content')

    <section class="form-wrapper">
        <div class="form">
            <h1 class="form-title">Ajouter un Bloc</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('recapPartBlock.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Titre du bloc :</label>
                    <input type="text" class="form-control" id="title" name="title" required
                        placeholder="Nom de l'exercice">
                </div>

                {{-- récap_part_id --}}
                <input type="hidden" name="recap_part_id" value="{{ $recapPart_id }}">

                {{-- color picker for theme --}}
                <div class="form-group">
                    {{-- <label for="theme" class="block text-sm font-medium mb-2 dark:text-white">Thème du bloc :</label>
                    <input type="color" class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700" id="theme" name="theme">
                     --}}
                    <label for="theme" class="block text-sm font-medium mb-2 dark:text-white">Thème du bloc :</label>
                    <select class="form-control text-white font-bold
                    " id="theme" name="theme" style="background-color: white;" onchange="changeBackground(this)">
                        <option value="">Sélectionner un thème</option>
                        <option value="Théorèmes">Théorèmes</option>
                        <option value="Définitions">Définitions</option>
                        <option value="Lemme">Lemme</option>
                        <option value="Remarque">Remarque</option>
                    </select>
                    <script>
                        function changeBackground(select) {
                            var selectedOption = select.options[select.selectedIndex];
                            if (selectedOption.value === 'Théorèmes') {
                                select.style.backgroundColor = '#E35F53';
                            } else if (selectedOption.value === 'Définitions') {
                                select.style.backgroundColor = '#4896ac';
                            } else if (selectedOption.value === 'Lemme') {
                                select.style.backgroundColor = '#65a986';
                            } else if (selectedOption.value === 'Remarque') {
                                select.style.backgroundColor = '#bababa';
                            } else {
                                select.style.backgroundColor = 'white';
                            }
                            select.style.backgroundColor = selectedOption.value;
                        }
                    </script>
                    </select>
                </div>

                <div class="form-group">
                    <label for="content">Contenu du bloc (LaTeX):</label>
                    <textarea class="form-control" id="content" name="content" rows="4" placeholder="Insérer le LaTeX ici..."></textarea>
                </div>

                <div class="form-group">
                    <label for="example">Exemple (LaTeX/optionnel) :</label>
                    <textarea class="form-control" id="clue" name="example" rows="4" placeholder="Insérer le LaTeX ici..."></textarea>
                </div>

                <button type="submit" class="submit-btn-form">Ajouter le bloc</button>
            </form>
        </div>
    </section>
@endsection
