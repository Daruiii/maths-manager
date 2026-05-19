@extends('layouts.app')

@section('content')
<section class="form-wrapper">
    <div class="form">
        <h1 class="form-title">Modifier le sous-chapitre</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('subchapter.update', $subchapter->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="title">Titre du sous-chapitre</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $subchapter->title) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description">{{ old('description', $subchapter->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="chapter_id">Chapitre</label>
                <select class="form-control" id="chapter_id" name="chapter_id" required>
                    @foreach ($chapters as $chapter)
                        <option value="{{ $chapter->id }}"{{ $chapter->id == $subchapter->chapter_id ? ' selected' : '' }}>{{ $chapter->title }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="submit-btn-form">Mettre à jour le sous-chapitre</button>
        </form>
    </div>
</section>
@endsection
