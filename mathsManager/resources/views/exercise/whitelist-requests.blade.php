@extends('layouts.app')

@section('title', 'Demandes d\'accès aux corrections')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Demandes d'accès aux corrections</h1>
                <p class="text-gray-600 mt-1">Gérer les demandes des étudiants pour accéder aux solutions</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('exercises.index') }}" 
                   class="px-4 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                    📋 Gérer les whitelists
                </a>
                <x-back-btn path="{{ route('exercises.index') }}">Retour aux exercices</x-back-btn>
            </div>
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

        <!-- Statistiques rapides -->
        <div class="grid md:grid-cols-2 gap-4 mb-6">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ $pendingRequests->count() }}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-yellow-800 font-semibold">Demandes en attente</p>
                        <p class="text-yellow-600 text-sm">À traiter</p>
                    </div>
                </div>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ $processedRequests->count() }}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-blue-800 font-semibold">Récemment traitées</p>
                        <p class="text-blue-600 text-sm">50 dernières</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demandes en attente -->
        @if($pendingRequests->count() > 0)
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Demandes en attente ({{ $pendingRequests->count() }})
                    </h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @foreach($pendingRequests as $request)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                        {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $request->user->name }}</p>
                                        <div class="flex items-center space-x-2">
                                            <p class="text-sm text-gray-600 exercise-content">{{ $request->exercise->name }} (n°{{ $request->exercise->order }})</p>
                                            <a href="{{ route('subchapter.show', $request->exercise->subchapter->id) }}?exercise={{ $request->exercise->id }}" 
                                               class="text-xs text-blue-600 hover:text-blue-800 underline" 
                                               title="Voir l'exercice dans son contexte">
                                                🔗 Voir
                                            </a>
                                        </div>
                                        @if($request->message)
                                            <p class="text-xs text-gray-500 italic mt-1">"{{ Str::limit($request->message, 80) }}"</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <button onclick="approveRequest({{ $request->id }}, '{{ $request->user->name }}')" 
                                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors">
                                        Accepter
                                    </button>
                                    <button onclick="rejectRequest({{ $request->id }}, '{{ $request->user->name }}')" 
                                            class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">
                                        Refuser
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center mb-6">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m6-7v7m4-7v7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune demande en attente</h3>
                <p class="text-gray-500">Toutes les demandes d'accès aux corrections ont été traitées.</p>
            </div>
        @endif

        <!-- Demandes traitées -->
        @if($processedRequests->count() > 0)
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Récemment traitées ({{ $processedRequests->count() }})
                    </h2>
                </div>
                <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                    @foreach($processedRequests as $request)
                        <div class="p-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 {{ $request->isApproved() ? 'bg-green-500' : 'bg-red-500' }} text-white rounded-full flex items-center justify-center text-xs">
                                        {{ $request->isApproved() ? '✓' : '✗' }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $request->user->name }}</p>
                                        <p class="text-xs text-gray-500 exercise-content">{{ $request->exercise->name }} - {{ $request->processed_at->format('d/m H:i') }}</p>
                                    </div>
                                </div>
                                
                                <button onclick="deleteRequest({{ $request->id }})" 
                                        class="text-gray-400 hover:text-red-600 transition-colors" 
                                        title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Info section -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-blue-700 text-sm">
                    <p><strong>Note:</strong> Approuver une demande ajoute automatiquement l'étudiant à la whitelist de l'exercice.</p>
                    <p class="mt-1">Les étudiants recevront une notification quand leur demande sera traitée.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'approbation -->
    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Approuver la demande</h3>
                
                <form id="approveForm" method="POST">
                    @csrf
                    <p class="text-sm text-gray-600 mb-4">
                        Approuver l'accès pour <strong id="approveUserName"></strong> ?
                    </p>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Message pour l'étudiant (optionnel)
                        </label>
                        <textarea 
                            name="admin_response" 
                            rows="2" 
                            maxlength="300"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                            placeholder="Ex: Bonne motivation, voici la correction..."
                        ></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModals()" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Approuver
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de rejet -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Refuser la demande</h3>
                
                <form id="rejectForm" method="POST">
                    @csrf
                    <p class="text-sm text-gray-600 mb-4">
                        Refuser l'accès pour <strong id="rejectUserName"></strong> ?
                    </p>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Raison du refus (obligatoire)
                        </label>
                        <textarea 
                            name="admin_response" 
                            rows="2" 
                            maxlength="300"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                            placeholder="Ex: Travaillez d'abord les exercices précédents..."
                        ></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModals()" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Refuser
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function approveRequest(requestId, userName) {
    document.getElementById('approveUserName').textContent = userName;
    document.getElementById('approveForm').action = `/admin/whitelist-requests/${requestId}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
}

function rejectRequest(requestId, userName) {
    document.getElementById('rejectUserName').textContent = userName;
    document.getElementById('rejectForm').action = `/admin/whitelist-requests/${requestId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModals() {
    document.getElementById('approveModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.add('hidden');
}

function deleteRequest(requestId) {
    if (confirm('Supprimer définitivement cette demande de l\'historique ?')) {
        fetch(`/admin/whitelist-requests/${requestId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => location.reload());
    }
}

</script>
@endsection
