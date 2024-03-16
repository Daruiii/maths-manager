@extends('layouts.app')

@section('content')
<section class="form-wrapper">
<div class="form">
    <h1 class="form-title">Modifier le chapitre</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('chapter.update', $chapter->id) }}" method="POST">
        @csrf
        @method('PATCH')
        
        <div class="form-group">
            <label for="title">Titre du chapitre</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $chapter->title) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description (optionnelle)</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $chapter->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="class_id">Classe</label>
            <select class="form-control" id="class_id" name="class_id" required>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}"{{ $class->id == $chapter->class_id ? ' selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="submit-btn-form">Mettre à jour le chapitre</button>
    </form>
</div>
</section>
@endsection
