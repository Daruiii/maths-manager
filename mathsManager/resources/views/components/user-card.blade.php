@props([
    'avatar',
    'name',
    'assignDsRoute',
    'assignSheetRoute',
    'lastDsGenerated',
    'resetLastDsRoute',
    'verified',
    'id'
])

<div class="user-card relative">
    <div class="user-card-details">
        <!-- Avatar -->
        <img src="{{ Str::startsWith($avatar, 'http') ? $avatar : asset('storage/images/' . $avatar) }}" 
             alt="Profile Picture" 
             class="w-12 h-12 rounded-full border border-black object-cover">

        <!-- User Details -->
        <p class="text-lg font-semibold turncate">{{ $name }}</p>
    </div>
    <hr class="my-2 h-0.5 bg-gray-400">
    <!-- Buttons Section -->
    <div class="flex flex-col items-center mt-4 gap-2">
        <!-- Assigner un DS -->
        <a href="{{ $assignDsRoute }}" class="text-white bg-blue-700 hover:bg-blue-900 rounded-lg px-4 py-2 w-full text-center">
            {{ __('Assigner un DS') }}
        </a>

        <!-- Assigner une fiche d'exercices -->
        <a href="{{ $assignSheetRoute }}" class="text-white bg-green-700 hover:bg-green-900 rounded-lg px-4 py-2 w-full text-center">
            {{ __('Assigner une fiche') }}
        </a>
    </div>

    <!-- DS+ Button & Activer/Désactiver -->
    <div class="flex justify-between items-center mt-4 gap-2 w-full">
        <!-- DS+ Button -->
        @php
            $isDsAvailable = !$lastDsGenerated || date('Y-m-d') !== (new DateTime($lastDsGenerated))->format('Y-m-d');
        @endphp
        @if ($isDsAvailable)
            <button type="submit" class="text-white bg-gray-500 rounded-full px-2 py-1 cursor-not-allowed">
                DS+
            </button>
        @else
            <form action="{{ $resetLastDsRoute }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-white bg-blue-500 hover:bg-blue-700 rounded-full px-2 py-1">
                    DS+
                </button>
            </form>
        @endif

        <!-- Activer/Désactiver -->
        @if ($verified)
            <form action="{{ route('user.unverify', ['id' => $id]) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-xs text-red-600 hover:text-red-900 p-2 border border-red-600 rounded-full bg-red-100">
                    Désactiver
                </button>
            </form>
        @else
            <form action="{{ route('user.verify', ['id' => $id]) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-xs text-green-600 hover:text-green-900 p-2 border border-green-600 rounded-full bg-green-100">
                    Activer
                </button>
            </form>
        @endif
    </div>
        <!-- Show More Details Button -->
    <a class="user-card-button hover:bg-gray-800"
         href="{{ route('user.show', ['id' => $id]) }}">
        Voir plus
    </a>
</div>

<style>
.user-card {
    width: 250px;
    height: auto;
    border-radius: 15px;
    background: #f5f5f5;
    position: relative;
    padding: 1.2rem;
    border: 2px solid #c3c6ce;
    transition: 0.5s ease-out;
    overflow: visible;
}

.user-card-details {
    color: black;
    height: auto;
    gap: .4em;
    display: flex;
    flex-direction: row;
    justify-content: start;
    align-items: center;
    font-size: 0.9rem;
    width: 100%;
    gap: 1rem;
}

.user-card-button {
    cursor: pointer;
    transform: translate(-50%, 135%);
    width: 80%;
    border-radius: 0.8rem;
    border: none;
    background-color: #000;
    color: #fff;
    font-size: 0.75rem;
    padding: .4rem 0.8rem;
    position: absolute;
    left: 50%;
    bottom: 2px;
    opacity: 0;
    transition: 0.5s ease-out;
    text-align: center; 
}

.user-card:hover {
    border-color: #000;
    box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.1);
}

.user-card:hover .user-card-button {
    transform: translate(-50%, 50%);
    opacity: 1;
}

.text-body {
    color: rgb(134, 134, 134);
}

.user-card:hover {
    border-color: #000;
    box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.1);
}

.user-card:hover .user-card-button {
    transform: translate(-50%, 50%);
    opacity: 1;
}
</style>
