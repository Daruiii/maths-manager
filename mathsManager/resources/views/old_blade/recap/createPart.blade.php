@extends('layouts.app')

@section('content')
<section class="form-wrapper">
    <div class="form">
        <h1 class="form-title">Ajouter une partie au récap</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('recapPart.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Titre </label>
                <input type="text" class="form-control" id="title" name="title">
            </div>

            {{-- desc --}}
            <div class="form-group">
                <label for="description">Description (optionnelle) </label>
                <input type="textarea" class="form-control" id="description" name="description">
            </div>

            <input type="hidden" name="recap_id" value="{{ $recap_id }}">

            <button type="submit" class="submit-btn-form">Ajouter la partie</button>
        </form>
    </div>
</section>
@endsection
