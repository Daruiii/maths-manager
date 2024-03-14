@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="quote-box">
        <p class="quote-text">{{ $classe->name }}</p>
    </div>
    @foreach ($chapters as $chapter)
    <h2 class="text-2xl font-bold mt-8">{{ $chapter->title }}</h1>
        @endforeach
</div>
@endsection