@extends('layouts.app')

@section('title', 'Mes demandes d\'accès aux corrections')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Mes demandes d'accès aux corrections</h1>
                <p class="text-gray-600 mt-1">Suivi de mes demandes pour accéder aux solutions</p>
            </div>
            <x-back-btn path="{{ route('home') }}">Retour à l'accueil</x-back-btn>
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

        @if($requests->count() > 0)
            <!-- Filtres simples -->
            <div class="flex justify-center mb-6">
                <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1">
                    <button onclick="filterRequests('all')" id="filter-all" 
                            class="filter-btn px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white">
                        Toutes ({{ $requests->count() }})
                    </button>
                    <button onclick="filterRequests('pending')" id="filter-pending" 
                            class="filter-btn px-4 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700">
                        En attente ({{ $requests->where('status', 'pending')->count() }})
                    </button>
                    <button onclick="filterRequests('approved')" id="filter-approved" 
                            class="filter-btn px-4 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700">
                        Approuvées ({{ $requests->where('status', 'approved')->count() }})
                    </button>
                    <button onclick="filterRequests('rejected')" id="filter-rejected" 
                            class="filter-btn px-4 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700">
                        Refusées ({{ $requests->where('status', 'rejected')->count() }})
                    </button>
                </div>
            </div>

            <!-- Liste simple -->
            <div class="bg-white rounded-lg shadow-md">
                @php
                    $sortedRequests = $requests->sortByDesc(function($request) {
                        return $request->isPending() ? 2 : ($request->isApproved() ? 1 : 0);
                    });
                @endphp
                
                <div class="divide-y divide-gray-200">
                    @foreach($sortedRequests as $request)
                        <div class="request-item p-4 hover:bg-gray-50" 
                             data-status="{{ $request->status }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 
                                        @if($request->isPending()) bg-orange-500 
                                        @elseif($request->isApproved()) bg-green-500 
                                        @else bg-red-500 
                                        @endif
                                        text-white rounded-full flex items-center justify-center text-sm font-medium">
                                        @if($request->isPending()) !
                                        @elseif($request->isApproved()) ✓
                                        @else ✗
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 exercise-content">{{ $request->exercise->name }} (n°{{ $request->exercise->order }})</p>
                                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                                            <span>{{ $request->created_at->format('d/m/Y') }}</span>
                                            @if($request->processed_at && !$request->isPending())
                                                <span>→</span>
                                                <span>{{ $request->processed_at->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                        @if($request->isPending()) bg-orange-100 text-orange-800 
                                        @elseif($request->isApproved()) bg-green-100 text-green-800 
                                        @else bg-red-100 text-red-800 
                                        @endif">
                                        @if($request->isPending()) En attente
                                        @elseif($request->isApproved()) Approuvée
                                        @else Rejetée
                                        @endif
                                    </span>
                                    
                                    <a href="{{ route('subchapter.show', $request->exercise->subchapter->id) }}?exercise={{ $request->exercise->id }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        Voir »
                                    </a>
                                </div>
                            </div>
                            
                            @if($request->admin_response)
                                <div class="mt-3 p-3 
                                    @if($request->isApproved()) bg-green-50 border-l-4 border-green-400 
                                    @else bg-red-50 border-l-4 border-red-400 
                                    @endif
                                    rounded">
                                    <p class="text-sm 
                                        @if($request->isApproved()) text-green-700 
                                        @else text-red-700 
                                        @endif">
                                        <strong>Réponse :</strong> {{ $request->admin_response }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- État vide -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-gray-400 mb-6">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune demande envoyée</h3>
                <p class="text-gray-500 mb-6">Vous n'avez pas encore demandé l'accès à des corrections d'exercices.</p>
                <p class="text-gray-600 text-sm">
                    Pour demander l'accès à une correction, consultez un exercice et cliquez sur 
                    <span class="font-semibold">« Demander l'accès à la correction »</span> 
                    si vous ne voyez pas la solution.
                </p>
            </div>
        @endif

        <!-- Info section -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-blue-700 text-sm">
                    <p><strong>Comment ça marche :</strong></p>
                    <ol class="mt-1 list-decimal list-inside space-y-1">
                        <li>Vous demandez l'accès à une correction d'exercice</li>
                        <li>L'équipe pédagogique examine votre demande</li>
                        <li>Vous recevrez une réponse et, si approuvée, l'accès à la solution</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function filterRequests(status) {
    // Réinitialiser tous les boutons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.className = 'filter-btn px-4 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700';
    });
    
    // Activer le bouton sélectionné
    document.getElementById('filter-' + status).className = 'filter-btn px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white';
    
    // Filtrer les éléments
    document.querySelectorAll('.request-item').forEach(item => {
        if (status === 'all' || item.dataset.status === status) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Afficher les en attente par défaut
document.addEventListener('DOMContentLoaded', function() {
    const pendingCount = {{ $requests->where('status', 'pending')->count() }};
    if (pendingCount > 0) {
        filterRequests('pending');
    }
});
</script>
@endsection
