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
                    <input type="text" class="form-control" id="title" name="title"
                        placeholder="Nom de l'exercice" value="{{ $recapPartBlock->title }}">
                </div>

                {{-- récap_part_id --}}
                <input type="hidden" name="recap_part_id" value="{{ $recapPartBlock->recap_part_id }}">

                {{-- color picker for theme --}}
                <div class="form-group">
                    <label for="theme" class="block text-sm font-medium mb-2 dark:text-white">Thème du bloc :</label>
                    <input type="color" class="p-1 h-10 w-14 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700" id="theme" name="theme" value="{{ $recapPartBlock->theme }}">
                </div>

                <div class="form-group">
                    <label for="content">Contenu du bloc (LaTeX):</label>
                    <textarea class="form-control" id="content" name="content" rows="4" placeholder="Insérer le LaTeX ici...">{{ $recapPartBlock->latex_content }}</textarea>
                </div>

                <button type="submit" class="submit-btn-form">Modifier le bloc</button>
            </form>
        </div>
    </section>
@endsection
