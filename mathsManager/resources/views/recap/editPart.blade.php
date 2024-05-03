@extends('layouts.app')

@section('content')
<section class="form-wrapper">
    <div class="form">
        <h1 class="form-title">Modifier une partie au récap</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('recapPart.update', $recapPart->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="title">Titre </label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $recapPart->title }}">
            </div>

            {{-- desc --}}
            <div class="form-group">
                <label for="description">Description (optionnelle) </label>
                <input type="textarea" class="form-control" id="description" name="description" value="{{ $recapPart->description }}">
            </div>

            <input type="hidden" name="recap_id" value="{{ $recapPart->recap_id }}">

            <button type="submit" class="submit-btn-form">Modifier la partie</button>
        </form>
    </div>
</section>
@endsection
