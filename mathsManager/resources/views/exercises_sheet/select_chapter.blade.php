@extends('layouts.app')

@section('content')
    <x-back-btn path="">Retour</x-back-btn>
    <section class="form-wrapper slide-left">
        <div class="form">
            <h1 class="form-title">Créer une fiche d'exercices</h1>
            <p class="form-explain mb-5">Sur quel chapitre souhaitez-vous créer la fiche d'exercices ?</p>

            <form action="{{ route('exercises_sheet.create') }}" method="GET">
                @csrf
                <div class="form-group">
                    <label>Chapitre :</label>
                    <select id="chapter" name="chapter_id" style="width: 100%;">
                        @foreach ($chapters as $chapter)
                            <option value="{{ $chapter->id }}">
                                {{ $chapter->title }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="student_id" value="{{ $studentId }}">
                <button type="submit">Suivant</button>
            </form>
        </div>
    </section>
@endsection
