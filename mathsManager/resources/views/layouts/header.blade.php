<header x-data="{ open: false }" class="bg-secondary-color text-text-color shadow">
    <nav class="px-4 py-2 flex items-center justify-between">
        <a href="{{ route('home') }}" class="logo">{{ config('app.name') }}</a>
        <!-- Menu Toggle Button -->
        <button @click="open = !open" class="md:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 text-text-color">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
        <!-- Links for large screens -->
        <div class="hidden md:flex space-x-4">
            @foreach ($classes as $class)
            <a href="{{ route('classe.show', $class->level) }}" class="link {{ request()->is("classe/{$class->level}") ? 'active' : '' }}">{{ $class->name }}</a>
        @endforeach
            <a href="{{ route('home') }}" class="link {{ request()->routeIs('home') ? 'active' : '' }}">Mes devoirs</a>
        </div>
        <div class="hidden md:flex">
            @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        @if (Auth::user()->role === 'admin')
                            <span class="text-blue-500">{{ Auth::user()->name }}</span>
                        @else
                        <span>{{ Auth::user()->name }}</span>
                        @endif
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
    <!-- Dropdown Links for small screens -->
    <div class="md:hidden absolute right-1 top-0 mt-14 p-4 rounded-lg shadow-xl bg-gray-100 space-y-4"  x-show="open" @click.away="open = false">
        @foreach ($classes as $class)
            <a href="{{ route('classe.show', $class->level) }}" class="block link {{ request()->is("classe/{$class->level}") ? 'active' : '' }}">{{ $class->name }}</a>
        @endforeach
            <a href="{{ route('home') }}" class="block link {{ request()->routeIs('home') ? 'active' : '' }}">Mes devoirs</a>
        @auth
        @if (Auth::user()->role === 'admin')
            <a href="{{ route('classe.index') }}" class="block bg-yellow-100 rounded-lg p-3 link {{ request()->is("admin") ? 'active' : '' }}">Admin</a>
        @endif
            <a href="{{ route('profile.edit') }}" class="block bg-blue-100 rounded-lg p-3 link {{ request()->is("profile") ? 'active' : '' }}">Mon profil</a>
            <a href="{{ route('logout') }}" class="block bg-red-100 rounded-lg p-3 link {{ request()->is("classe/{$class->level}") ? 'active' : '' }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Se déconnecter</a>
        @else
            <a href="{{ route('login') }}" class="block bg-green-100 rounded-lg p-3 link {{ request()->is("classe/{$class->level}") ? 'active' : '' }}">Se connecter</a>
        @endauth
    </div>
</nav>
@auth
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
@endauth
</header>
