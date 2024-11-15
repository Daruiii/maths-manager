@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Gestion des Contenus Publics</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($contents as $content)
            <div class="p-4 bg-white shadow rounded-lg">
                <h2 class="text-lg font-bold">{{ $content->title ?? 'Titre non défini' }}</h2>
                <p class="text-sm text-gray-600">{{ Str::limit($content->content, 100) }}</p>
                
                <a href="{{ route('content.edit', $content->section) }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                    Éditer
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
