@extends('layouts.app')

@section('content')
    <x-back-btn path="{{ route('admin') }}"> Retour</x-back-btn>

    <div class="container mx-auto mb-8 mt-6">
        <div class="flex justify-center items-center pt-6">
            <div>
                <h2 class="text-lg leading-6 font-medium text-gray-900">Mes corrections</h2>
            </div>
        </div>
        <div class="flex justify-between items-center py-3 flex-wrap gap-2">
            {{-- search bar --}}
            <x-search-bar-admin action="{{ route('correctionRequest.myCorrections') }}" placeholder="Rechercher un élève ..."
                name="search" />
        </div>

        @if ($correctionRequests->count())
            <div class="w-full flex flex-wrap justify-center items-center gap-3">
                @foreach ($correctionRequests as $index => $correctionRequest)
                    <div class="quiz-card">
                        <div class="quiz-card-details">
                            <div class="flex flex-row justify-center items-center">
                                @if (Str::startsWith($correctionRequest->user->avatar, 'http'))
                                    <img src="{{ $correctionRequest->user->avatar }}"
                                        class="w-12 h-12 rounded-full border border-black object-cover hover:brightness-50 transition duration-300  "alt="Profile Picture">
                                @else
                                    <img src="{{ asset('storage/images/' . $correctionRequest->user->avatar) }}"
                                        class="w-12 h-12 rounded-full border border-black object-cover hover:brightness-50 transition duration-300"
                                        alt="Profile Picture">
                                @endif
                            </div>
                            <p class="text-body truncate">{{ $correctionRequest->user->name }}</p>
                            <p class="text-title text-center text-sm"> <a href="{{ route('ds.show', $correctionRequest->ds_id) }}"
                                class="text-indigo-600 hover:text-indigo-900 underline">
                                DS n°{{ $correctionRequest->ds_id }}
                            </a></p>
                            <div
                                class="quiz-card-score {{ $correctionRequest->status == 'pending' ? 'score-low' : 'score-high' }}">
                                <p class="text-white text-center">{{ ucfirst($correctionRequest->status) }}</p>
                            </div>
                        </div>
                        <button class="quiz-card-button"
                            onclick="window.location.href='{{ route('correctionRequest.show', $correctionRequest->ds_id) }}'">Voir</button>
                    </div>
                @endforeach
            </div>
        @else
        <div class="flex justify-center flex-col items-center w-full h-20 ">
            <h2 class="text-gray-500">Aucune demande en attente</h2>
            <div class="flex justify-center items-center w-1/2">
                <p class="text-center text-gray-500 text-xs">Veuillez revenir plus tard</p>
            </div>
        </div>
        @endif

        <!-- Pagination -->
        <div class="flex justify-center mt-4">
            {{ $correctionRequests->links() }}
        </div>
    </div>
@endsection
