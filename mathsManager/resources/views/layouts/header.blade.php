<header>
    <nav class="px-4 py-2 flex justify-between items-center">
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="logo">{{ config('app.name') }}</a>
        </div>

        <div class="hidden md:flex space-x-4">
            <a href="{{ route('classe', 'premiere-spe') }}" class="link {{ request()->routeIs('classe', 'premiere-spe') ? 'active' : '' }}">Première SPE</a>
            <a href="{{ route('home') }}" class="link">Terminale SPE</a>
            <a href="{{ route('home') }}" class="link">Maths expertes</a>
            <a href="{{ route('home') }}" class="link">Mes devoirs</a>
        </div>
        <div class="auth-links">
            @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <span>{{ Auth::user()->name }}</span>
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" viewBox="0 0 24 24" class="ml-2 w-4 h-4">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="popup absolute right-0 mt-2 py-2 w-48 rounded-md shadow-xl z-20">
                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-sm capitalize">
                            Mon profil
                        </a>
                        <a href="{{ route('logout') }}"
                            class="block px-4 py-2 text-sm capitalize"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Se déconnecter
                        </a>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="link">Se connecter</a>
            @endauth
        </div>
    </nav>
</header>
