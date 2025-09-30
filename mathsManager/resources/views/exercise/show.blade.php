@extends('layouts.app')

@section('title', ($exercise->name ?? 'Exercice') . ' - Maths Manager')
@section('meta_description', 'Exercice de maths : ' . ($exercise->name ?? 'Sans titre') . '. Consultez l’énoncé, la difficulté et la solution.')
@section('canonical', url()->current())

@section('content')
    <div class="container mx-auto">
        <!-- Boutons de navigation -->
        <div class="flex items-center w-full mt-5 space-x-4">
            <!-- Bouton retour -->
            <x-back-btn path="">Retour</x-back-btn>
        </div>

        <!-- Contenu de l'exercice -->
        <div class="p-6 my-5 bg-white rounded-lg shadow-md w-2/3 mx-auto">
            <!-- En-tête avec le nom de l'exercice -->
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-blue-700 exercise-content">
                    {{ $exercise->name ?? 'Exercice Sans Titre' }} n°{{ $exercise->order }} #{{
                        $exercise->id }}
                </h1>
                @auth
                    @if (Auth::user()->role === 'admin')
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('exercise.whitelist.show', $exercise->id) }}" 
                               class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-md shadow-sm text-sm leading-4 font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                🔒 Corrections
                            </a>
                            <x-button-edit href="{{ route('exercise.edit', $exercise->id) }}" />
                            <x-button-delete href="{{ route('exercise.destroy', $exercise->id) }}" entity="cet exercice" entityId="exercise{{ $exercise->id }}" />
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Énoncé -->
            <div class="mt-6">
                <x-stars-difficulty starsActive="{{ $exercise->difficulty }}" id="rating{{ $exercise->id }}" />
                <h2 class="text-lg font-semibold">Énoncé :</h2>
                <div class="mt-2 p-4 bg-gray-50 rounded border border-gray-200 exercise-content cmu-serif">
                    {!! $exercise->statement !!}
                </div>
            </div>

            <!-- Solution -->
            @if ($exercise->solution)
                @auth
                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'teacher' || $exercise->isWhitelisted(Auth::id()))
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold">Solution :</h2>
                            <div class="mt-2 p-4 bg-green-50 rounded border border-green-200 solution-content cmu-serif">
                                {!! $exercise->solution !!}
                            </div>
                        </div>
                    @else
                        <div class="mt-6">
                            <div class="p-4 bg-red-50 rounded border border-red-200 text-center">
                                <p class="text-red-700">🔒 <strong>Solution masquée</strong></p>
                                <p class="text-red-600 text-sm mt-1">Vous n'avez pas accès à la solution de cet exercice.</p>
                                
                                @if (Auth::user()->role === 'student')
                                    <div class="mt-3">
                                        @if ($exercise->hasWhitelistRequest(Auth::id()))
                                            <span class="inline-flex items-center px-3 py-2 bg-yellow-100 rounded-lg border border-yellow-300 text-yellow-600 text-sm font-medium">
                                                ⏳ Demande en cours
                                            </span>
                                        @else
                                            <button onclick="openRequestModal({{ $exercise->id }}, '{{ $exercise->name }}', {{ $exercise->order }})" 
                                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                Demander l'accès
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="mt-6">
                        <div class="p-4 bg-red-50 rounded border border-red-200 text-center">
                            <p class="text-red-700">🔒 <strong>Solution masquée</strong></p>
                            <p class="text-red-600 text-sm mt-2">Vous devez être connecté pour accéder à la solution.</p>
                        </div>
                    </div>
                @endauth
            @endif

            <!-- Indice -->
            @if ($exercise->clue)
                <div class="mt-6">
                    <h2 class="text-lg font-semibold">Indice :</h2>
                    <div class="mt-2 p-4 bg-yellow-50 rounded border border-yellow-200 clue-content cmu-serif">
                        {!! $exercise->clue !!}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(Auth::check() && Auth::user()->role === 'student')
        <!-- Modal de demande de correction -->
        <div id="requestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeRequestModal(event)">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6" onclick="event.stopPropagation()">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Demander l'accès à la correction</h3>
                        <button onclick="closeRequestModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form id="requestForm" method="POST">
                        @csrf
                        <p class="text-sm text-gray-600 mb-4">
                            Exercice : <strong id="exerciseName"></strong>
                        </p>
                        
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Pourquoi avez-vous besoin de cette correction ? (optionnel)
                            </label>
                            <textarea 
                                name="message" 
                                id="message" 
                                rows="3" 
                                maxlength="500"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ex: Je n'arrive pas à comprendre cette méthode..."
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">Un message aide l'équipe à mieux comprendre votre besoin.</p>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeRequestModal()" 
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Annuler
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Envoyer ma demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
@if(Auth::check() && Auth::user()->role === 'student')
<script>
function openRequestModal(exerciseId, exerciseName, exerciseOrder) {
    document.getElementById('exerciseName').textContent = `${exerciseName} (n°${exerciseOrder})`;
    document.getElementById('requestForm').action = `/exercise/${exerciseId}/request-whitelist`;
    document.getElementById('message').value = '';
    document.getElementById('requestModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeRequestModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('requestModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Fermer la modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRequestModal();
    }
});
</script>
@endif
@endsection
