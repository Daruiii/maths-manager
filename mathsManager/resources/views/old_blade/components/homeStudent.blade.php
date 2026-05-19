@props([
    'totalDS',
    'notStartedDS',
    'inProgressDS',
    'sentDS',
    'correctedDS',
    'averageGrade',
    'scores',
    'goodAnswers',
    'badAnswers',
])

<div class="flex flex-col w-full lg:w-8/12 bg-white p-4 rounded-lg border border-gray-200 shadow-md">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-sm font-bold">Devoirs ({{ $totalDS ?? 'N/A' }})</h3>
        <x-btn-see href="{{ route('ds.myDS', Auth::user()->id) }}">
            {{ __('Voir mes devoirs') }}
        </x-btn-see>
    </div>
    
    <div class="flex flex-col lg:flex-row justify-between gap-4">
        <div class="flex flex-col gap-1 bg-gray-200 p-2 rounded w-full lg:w-1/2 justify-center items-center">
            <div class="flex flex-wrap gap-3 w-full items-center justify-center">
                <div class="bg-[#019875] p-3 rounded w-full md:w-1/3 flex flex-col items-center gap-2 min-w-32 max-h-20">
                    <img src="{{ asset('storage/images/dsStatus/to-do.png') }}" alt="Pas commencé" class="w-7 h-7">
                    <div class="flex flex-row items-center gap-1">
                        <p class="text-xs font-semibold">{{ $notStartedDS ?? 'N/A' }}</p>
                        <p class="text-xs">à faire</p>
                    </div>
                </div>
                <div class="bg-[#fda054] p-3 rounded w-full md:w-1/3 flex flex-col items-center gap-2 min-w-32 max-h-20">
                    <img src="{{ asset('storage/images/dsStatus/wip.png') }}" alt="En cours" class="w-7 h-7">
                    <div class="flex flex-row items-center gap-1 w-full justify-center">
                        <p class="text-xs font-semibold">{{ $inProgressDS ?? 'N/A' }}</p>
                        <p class="text-xs">en cours</p>
                    </div>
                </div>
                <div class="bg-[#318CE7] p-3 rounded w-full md:w-1/3 flex flex-col items-center gap-2 min-w-32 max-h-20">
                    <img src="{{ asset('storage/images/dsStatus/sent.png') }}" alt="Envoyé" class="w-7 h-7">
                    <div class="flex flex-row items-center gap-1">
                        <p class="text-xs font-semibold">{{ $sentDS ?? 'N/A' }}</p>
                        <p class="text-xs">envoyé{{ $sentDS > 1 ? 's' : '' }}</p>
                    </div>
                </div>
                <div class="bg-red-200 p-3 rounded w-full md:w-1/3 flex flex-col items-center gap-2 min-w-32 max-h-20">
                    <img src="{{ asset('storage/images/dsStatus/corrected.png') }}" alt="Corrigé" class="w-7 h-7">
                    <div class="flex flex-row items-center gap-1">
                        <p class="text-xs font-semibold">{{ $correctedDS ?? 'N/A' }}</p>
                        <p class="text-xs">corrigé{{ $correctedDS > 1 ? 's' : '' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @php
            $averageGradeColor = 'border-red-500 text-red-500'; // Default to red
            if ($averageGrade > 14) {
                $averageGradeColor = 'border-green-500 text-green-500';
            } elseif ($averageGrade >= 10) {
                $averageGradeColor = 'border-orange-500 text-orange-500';
            }
        @endphp

        <div class="flex flex-col items-center gap-4 bg-gray-200 p-4 rounded w-full lg:w-1/2 justify-center">
            <!-- Cercle pour la moyenne -->
            <div class="relative flex items-center justify-center w-24 h-24 bg-white border-4 {{ $averageGradeColor }} rounded-full">
                <p class="text-2xl font-bold {{ $averageGradeColor }}">{{ $averageGrade ?? 'N/A' }}</p>
                <p class="absolute bottom-2 text-xs">/ 20</p>
            </div>
            <p class="text-sm text-center">Moyenne des devoirs</p>
        </div>
    </div>
</div>

<div class="flex flex-col w-full lg:w-56 bg-white p-4 rounded-lg border border-gray-200 shadow-md">
    <h3 class="text-sm font-bold">Quizz (10 derniers)</h3>
    <p class="text-xs">Résultat moyen : {{ $scores ?? 'N/A' }} / 10</p>
    <div class="flex flex-col gap-2 mt-2 p-2 rounded w-full justify-between items-center">
        <x-progress-circle goodAnswers="{{ isset($goodAnswers) ? $goodAnswers : '50' }}"
            badAnswers="{{ isset($badAnswers) ? $badAnswers : '50' }}" />
    </div>
</div>

<!-- Section Mes demandes de corrections -->
<div class="flex flex-col w-full lg:w-56 bg-white p-4 rounded-lg border border-gray-200 shadow-md">
    <h3 class="text-sm font-bold">Demandes de corrections</h3>
    
    @php
        $pendingCount = Auth::user()->whitelistRequests()->pending()->count();
        $approvedCount = Auth::user()->whitelistRequests()->where('status', 'approved')->count();
        $totalRequests = Auth::user()->whitelistRequests()->count();
    @endphp
    
    <div class="text-center mt-3">
        @if($pendingCount > 0)
            <div class="w-12 h-12 bg-orange-500 text-white rounded-full flex items-center justify-center text-lg font-bold mx-auto mb-2">
                {{ $pendingCount }}
            </div>
            <p class="text-xs font-medium text-orange-600">En attente</p>
        @elseif($approvedCount > 0)
            <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                🎉
            </div>
            <p class="text-xs font-medium text-green-600">{{ $approvedCount }} correction(s) débloquée(s) !</p>
        @else
            <div class="w-12 h-12 bg-gray-400 text-white rounded-full flex items-center justify-center mx-auto mb-2">
                📝
            </div>
            <p class="text-xs text-gray-500">Aucune demande</p>
        @endif
    </div>
    
    <div class="mt-3">
        <x-btn-see href="{{ route('whitelist-requests.my') }}" class="w-full text-center">
            @if($totalRequests > 0)
                Voir mes {{ $totalRequests }} demande(s)
            @else
                Mes demandes
            @endif
        </x-btn-see>
    </div>
</div>
