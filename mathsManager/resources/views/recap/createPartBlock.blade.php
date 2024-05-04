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
                        <option value="#ff6961">Théorèmes</option>
                        <option value="#A9CBD7">Définitions</option>
                        <option value="#B0F2B6">lemme</option>
                        <option value="#CFCFC4">remarque</option>
                    </select>
                    <script>
                        function changeBackground(select) {
                            var selectedOption = select.options[select.selectedIndex];
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
