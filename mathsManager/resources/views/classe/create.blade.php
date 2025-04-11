@extends('layouts.app')

@section('content')
<section class="form-wrapper">
    <div class="form">
        <h1 class="form-title">Ajouter une Classe</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('classe.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nom de la classe</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="level">Niveau</label>
                <input type="text" class="form-control" id="level" name="level" required>
            </div>

            <div class="form-group">
                <label for="hidden">Caché</label>
                <input type="checkbox" id="hidden" name="hidden" value="1">
            </div>

            <button type="submit" class="submit-btn-form">Ajouter la classe</button>
        </form>
    </div>
</section>
@endsection
