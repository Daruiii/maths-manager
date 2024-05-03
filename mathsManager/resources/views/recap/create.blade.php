@extends('layouts.app')

@section('content')
<section class="form-wrapper">
    <div class="form">
        <h1 class="form-title">Ajouter une fiche récap</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('recap.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Titre du récap (optionnelle) </label>
                <input type="text" class="form-control" id="title" name="title">
            </div>

            <input type="hidden" name="chapter_id" value="{{ $chapter_id }}">

            <button type="submit" class="submit-btn-form">Ajouter la fiche</button>
        </form>
    </div>
</section>
@endsection
