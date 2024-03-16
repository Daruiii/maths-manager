@extends('layouts.app')

@section('content')
<section class="form-wrapper">
    <div class="form">
        <h1 class="form-title">Modifier la Classe</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('classe.update', $classe->level) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Nom de la classe</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $classe->name) }}" required>
            </div>

            <div class="form-group">
                <label for="level">Niveau</label>
                <input type="text" class="form-control" id="level" name="level" value="{{ old('level', $classe->level) }}" required>
            </div>
            
            <button type="submit" class="submit-btn-form">Mettre à jour la classe</button>
        </form>
    </div>
</section>
@endsection
