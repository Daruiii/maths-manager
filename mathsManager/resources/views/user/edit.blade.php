@extends('layouts.app')

@section('content')
<section class="form-wrapper">
<div class="form">
    <h1 class="form-title">Modifier l'utilisateur</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PATCH')
        
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        @if($roles) <!-- Supposant que vous avez une liste de rôles -->
        <div class="form-group">
            <label for="role">Rôle</label>
            <select class="form-control" id="role" name="role">
                @foreach ($roles as $role)
                    <option value="{{ $role }}"{{ $role == $user->role ? ' selected' : '' }}>{{ $role }}</option>
                @endforeach
            </select>
        </div>
        @endif
        
        <button type="submit" class="submit-btn-form">Mettre à jour l'utilisateur</button>
    </form>
</div>
</section>
@endsection
