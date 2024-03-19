@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Dashboard Admin</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Bouton pour les utilisateurs -->
        <a href="{{ route('users.index') }}" class="p-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Gérer les utilisateurs
        </a>
        
        <!-- Bouton pour les classes -->
        <a href="{{ route('classe.index') }}" class="p-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Gérer les classes
        </a>
        
        <!-- Ajoutez plus de boutons pour d'autres entités si nécessaire -->
    </div>
</div>
@endsection
