@extends('layouts.app')

@section('title', 'Gestion des corrections - ' . $exercise->name)

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gestion des corrections</h1>
                <p class="text-gray-600 mt-1">
                    Exercice: <strong>{{ $exercise->name }}</strong> 
                    ({{ $exercise->subchapter->chapter->classe->name ?? 'N/A' }} - {{ $exercise->subchapter->name ?? 'N/A' }})
                </p>
            </div>
            <x-back-btn path="{{ route('exercise.show', $exercise->id) }}">Retour à l'exercice</x-back-btn>
        </div>

        <!-- Messages flash -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Ajouter un étudiant -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ajouter un étudiant à la whitelist</h2>
                
                <form method="POST" action="{{ route('exercise.whitelist.add', $exercise->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Sélectionner un étudiant
                        </label>
                        <select name="user_id" id="user_id" 
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                required>
                            <option value="">-- Choisir un étudiant --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Ajouter à la whitelist
                    </button>
                </form>
            </div>

            <!-- Liste des étudiants whitelistés -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Étudiants ayant accès à la correction
                    <span class="text-sm font-normal text-gray-500">({{ $exercise->whitelistedUsers->count() }})</span>
                </h2>

                @if($exercise->whitelistedUsers->count() > 0)
                    <div class="space-y-2">
                        @foreach($exercise->whitelistedUsers as $student)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <span class="text-gray-800">{{ $student->name }}</span>
                                    <span class="text-gray-500 text-sm ml-2">({{ $student->email }})</span>
                                </div>
                                <form method="POST" action="{{ route('exercise.whitelist.remove', [$exercise->id, $student->id]) }}" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir retirer {{ $student->name }} de la whitelist ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 focus:outline-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a9 9 0 01-13.5 9.002V21z"></path>
                        </svg>
                        <p>Aucun étudiant n'a encore accès à la correction de cet exercice.</p>
                        <p class="text-sm mt-1">Ajoutez des étudiants pour qu'ils puissent voir la solution.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info section -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-blue-700 text-sm">
                    <p><strong>Note:</strong> Les corrections d'exercices sont masquées par défaut pour tous les étudiants.</p>
                    <p class="mt-1">Les administrateurs et enseignants ont toujours accès aux corrections. Seuls les étudiants ajoutés à la whitelist peuvent voir les solutions.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
