<div
    class="flex flex-col justify-start bg-white border-2 border-gray-200 items-start p-4 w-full md:w-1/2 flex-grow rounded-lg">
    <div class="w-full flex justify-start items-center">
        <h2 class="text-lg leading-6 font-medium text-gray-900">Mes corrections</h2>
    </div>
    <div class="flex w-full justify-between items-center py-3 flex-wrap gap-2">
        <x-search-bar-admin action="{{ route('home') }}" placeholder="Rechercher un élève ..." name="search" />
        <form id="filter-form" method="GET" action="{{ route('home') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <x-optionsFilter :status="request('status', 'pending')" />
        </form>
    </div>
    @php
        $correctionRequests = session('correctionRequests');
        $ds = session('ds');
    @endphp
    @if ($correctionRequests && count($correctionRequests) > 0)
        <div class="w-full flex flex-wrap justify-center items-center gap-4">
            @foreach ($correctionRequests as $index => $correctionRequest)
                <div class="correction-card">
                    <div class="correction-card-details">
                        <div class="flex flex-row justify-center items-center">
                            @if (Str::startsWith($correctionRequest->user->avatar, 'http'))
                                <img src="{{ $correctionRequest->user->avatar }}"
                                    class="w-12 h-12 rounded-full border border-black object-cover hover:brightness-50 transition duration-300"
                                    alt="Profile Picture">
                            @else
                                <img src="{{ asset('storage/images/' . $correctionRequest->user->avatar) }}"
                                    class="w-12 h-12 rounded-full border border-black object-cover hover:brightness-50 transition duration-300"
                                    alt="Profile Picture">
                            @endif
                        </div>
                        <p class="text-body truncate">{{ $correctionRequest->user->name }}</p>
                        <p class="text-title text-center text-sm">
                            <a href="{{ route('ds.show', $correctionRequest->ds_id) }}"
                                class="text-indigo-600 hover:text-indigo-900 underline">
                                DS n°{{ $correctionRequest->ds_id }}
                            </a>
                        </p>
                        <div
                            class="correction-card-score {{ $correctionRequest->status == 'pending' ? 'score-low' : 'score-high' }}">
                            <p class="text-white text-center">{{ ucfirst($correctionRequest->status) }}</p>
                        </div>
                    </div>
                    <button class="correction-card-button"
                        onclick="window.location.href='{{ route('correctionRequest.show', $correctionRequest->ds_id) }}'">Voir</button>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex justify-center flex-col items-center w-full h-20">
            <h2 class="text-gray-500">Aucune demande en attente</h2>
            <div class="flex justify-center items-center w-1/2">
                <p class="text-center text-gray-500 text-xs">Veuillez revenir plus tard</p>
            </div>
        </div>
    @endif

    <!-- Pagination -->
    @if ($correctionRequests && count($correctionRequests) > 0)
    <div class="flex justify-center mt-4">
        {{ $correctionRequests->links('vendor.pagination.simple-tailwind') }}
    </div>
    @endif
</div>
<div
    class="flex flex-col justify-start items-center p-4 bg-white border-2 border-gray-200 rounded-md max-w-full min-w-80 max-h-96">
    <h2 class="text-base leading-6 font-medium text-gray-900">Devoirs </h2>
    <a href="{{ route('ds.index') }}" class="text-xs text-black hover:text-indigo-900 underline">Voir en détail</a>
    <div class="overflow-y-auto w-11/12 h-full bg-gray-100 rounded-md p-3 mt-2 max-h-96">
        @if ($ds && count($ds) > 0)
        @foreach ($ds as $d)
            <div class="flex flex-row justify-between items-center w-full py-2">
                <p class="text-xs truncate w-1/2">{{ $d->user->name }}</p>
                @if ($d->status == 'not_started')
                    <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                @elseif ($d->status == 'ongoing')
                    <div class="w-4 h-4 bg-orange-500 rounded-full"></div>
                @else
                    <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                @endif
                <a href="{{ route('ds.show', $d->id) }}"
                    class="text-xs text-black hover:text-indigo-900 underline">Voir</a>
            </div>
        @endforeach
        @else
            <div class="flex justify-center items-center w-full h-20">
                <h2 class="text-gray-500">Aucun devoir</h2>
                <div class="flex justify-center items-center w-1/2">
                    <p class="text-center text-gray-500 text-xs">Veuillez revenir plus tard</p>
                </div>
            </div>
        @endif
    </div>
    <div class="flex flex-row justify-between items-end w-11/12 py-2 gap-2">
        <div class="flex flex-row items-center gap-1">
            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
            <p class="font-size-xxsmall">Pas commencé</p>
        </div>
        <div class="flex flex-row items-center gap-1">
            <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
            <p class="font-size-xxsmall">En cours</p>
        </div>
        <div class="flex flex-row items-center gap-1">
            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            <p class="font-size-xxsmall">Terminé</p>
        </div>
    </div>
</div>

<!-- Section Demandes de whitelist -->
<div class="flex flex-col justify-start items-center p-4 bg-white border-2 border-orange-200 rounded-md max-w-full min-w-80">
    <h2 class="text-base leading-6 font-medium text-gray-900">Demandes de corrections</h2>
    
    @php
        $pendingCount = App\Models\WhitelistRequest::pending()->count();
    @endphp
    
    <div class="w-full mt-3">
        @if($pendingCount > 0)
            <div class="text-center p-4">
                <div class="w-16 h-16 bg-orange-500 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-3">
                    {{ $pendingCount }}
                </div>
                <p class="text-sm font-medium text-gray-900 mb-2">{{ $pendingCount }} demande(s) en attente</p>
                <a href="{{ route('whitelist-requests.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-md text-sm hover:bg-orange-700 transition-colors">
                    Traiter les demandes
                </a>
            </div>
        @else
            <div class="text-center p-4">
                <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-3">
                    ✓
                </div>
                <p class="text-sm text-gray-600">Aucune demande en attente</p>
                <p class="text-xs text-gray-500 mt-1">Toutes les demandes ont été traitées</p>
            </div>
        @endif
    </div>
</div>
