@extends('layouts.app')

@section('content')
<section class="form-wrapper">
    <div class="form">
        <h1 class="form-title">Ajouter un sous-chapitre</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('subchapter.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Titre du sous-chapitre</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description (optionnelle)</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>

            <input type="hidden" name="chapter_id" value="{{ $chapter_id }}">

            <div class="form-group">
                <label for="order">Ordre</label>
                <input type="number" class="form-control" id="order" name="order" value="{{ $nextOrder }}" required>
            </div>

            <button type="submit" class="submit-btn-form">Ajouter le sous-chapitre</button>
        </form>
    </div>
</section>
@endsection
