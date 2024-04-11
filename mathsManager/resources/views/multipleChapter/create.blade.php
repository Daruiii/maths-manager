@extends('layouts.app')

@section('content')
<section class="form-wrapper">
<div class="form">
    <h1 class="form-title">Ajouter un chapitre</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('multiple_chapter.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Titre du chapitre</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="description">Description (optionnelle)</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="theme">Thème (no homo)</label>
            <select class="form-control" id="theme" name="theme">
                @foreach ($themeColors as $themeColor)
                    <option value="{{ $themeColor }}" style="background-color: {{ $themeColor }};">
                        {{ array_search($themeColor, $themeColors) }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="submit-btn-form">Ajouter le chapitre</button>
    </form>
</div>
</section>
@endsection
