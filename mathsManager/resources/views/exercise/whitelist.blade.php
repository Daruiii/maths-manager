@extends('layouts.app')

@section('title', 'Gestion des corrections - ' . $exercise->name)

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gestion des corrections</h1>
                <p class="text-gray-600 mt-1">
                    Exercice: <strong>{{ $exercise->name }}</strong> (n°{{ $exercise->order }})
                    <br><span class="text-sm">{{ $exercise->subchapter->chapter->classe->name ?? 'N/A' }} - {{ $exercise->subchapter->name ?? 'N/A' }}</span>
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('whitelist-requests.index') }}" 
                   class="px-4 py-2 border border-orange-300 rounded-md text-sm font-medium text-orange-700 bg-orange-50 hover:bg-orange-100 transition-colors">
                    📝 Toutes les demandes
                </a>
                <x-back-btn path="{{ route('subchapter.show', $exercise->subchapter->id) }}?exercise={{ $exercise->id }}">Retour au sous-chapitre</x-back-btn>
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

        <!-- Demandes en attente pour cet exercice -->
        @if($pendingRequests->count() > 0)
            <div class="mb-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-orange-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Demandes d'accès en attente ({{ $pendingRequests->count() }})
                </h3>
                <div class="space-y-3">
                    @foreach($pendingRequests as $request)
                        <div class="bg-white border border-orange-200 rounded-md p-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $request->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->created_at->format('d/m/Y à H:i') }}</p>
                                    @if($request->message)
                                        <p class="text-sm text-gray-600 mt-1 italic">"{{ $request->message }}"</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <form method="POST" action="{{ route('whitelist-requests.approve', $request->id) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Accepter
                                    </button>
                                </form>
                                <button onclick="openRejectModal('{{ $request->id }}', '{{ $request->user->name }}')"
                                        class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Refuser
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Ajouter un étudiant -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Ajouter des étudiants</h2>
                    <a href="{{ route('whitelist-requests.index') }}" 
                       class="text-sm text-blue-600 hover:text-blue-800 underline">
                        📝 Voir les demandes
                    </a>
                </div>
                
                <!-- Recherche rapide -->
                <div class="mb-4">
                    <input type="text" 
                           id="studentSearch" 
                           placeholder="Rechercher un étudiant par nom..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           onkeyup="filterStudents()">
                </div>
                
                <!-- Liste des étudiants non whitelisés -->
                <div id="studentList" class="max-h-64 overflow-y-auto border border-gray-200 rounded-md">
                    @php
                        $whitelistedIds = $exercise->whitelistedUsers->pluck('id')->toArray();
                        $availableStudents = $students->reject(function($student) use ($whitelistedIds) {
                            return in_array($student->id, $whitelistedIds);
                        });
                    @endphp
                    
                    @foreach($availableStudents as $student)
                        <div class="student-item p-3 border-b border-gray-100 hover:bg-gray-50 flex items-center justify-between" 
                             data-name="{{ strtolower($student->name) }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gray-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                </div>
                            </div>
                            
                            <form method="POST" action="{{ route('exercise.whitelist.add', $exercise->id) }}" class="inline-block">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $student->id }}">
                                <button type="submit" 
                                        class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors">
                                    + Ajouter
                                </button>
                            </form>
                        </div>
                    @endforeach
                    
                    @if($availableStudents->count() === 0)
                        <div class="p-4 text-center text-gray-500">
                            <p>Tous les étudiants ont déjà accès à cette correction</p>
                        </div>
                    @endif
                </div>
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
        
        <!-- Modal de rejet de demande -->
        <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50" onclick="closeRejectModal(event)">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" onclick="event.stopPropagation()">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Refuser la demande</h3>
                    <p class="text-gray-600 mb-4">Voulez-vous refuser la demande de <strong id="rejectStudentName"></strong> ?</p>
                    
                    <form id="rejectForm" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="rejectMessage" class="block text-sm font-medium text-gray-700 mb-2">
                                Message pour l'étudiant (optionnel)
                            </label>
                            <textarea id="rejectMessage" 
                                      name="message" 
                                      rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Expliquez pourquoi la demande est refusée..."></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="closeRejectModal()" 
                                    class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                Annuler
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                Refuser
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function filterStudents() {
    const search = document.getElementById('studentSearch').value.toLowerCase();
    const students = document.querySelectorAll('.student-item');
    
    students.forEach(function(student) {
        const name = student.getAttribute('data-name');
        if (name.includes(search)) {
            student.style.display = 'flex';
        } else {
            student.style.display = 'none';
        }
    });
}

// Fonctions pour la modal de rejet
function openRejectModal(requestId, studentName) {
    document.getElementById('rejectStudentName').textContent = studentName;
    document.getElementById('rejectForm').action = '/admin/whitelist-requests/' + requestId + '/reject';
    document.getElementById('rejectMessage').value = '';
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function closeRejectModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}

// Fermer avec Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRejectModal();
    }
});
</script>
@endsection
