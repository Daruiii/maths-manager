<header class="bg-secondary-color text-text-color" id="top">
    <nav class="px-4 py-2 flex items-center justify-between">
        <a href="{{ route('home') }}" class="logo">{{ config('app.name') }}</a>
        <!-- Menu Toggle Button -->
        <button id="menu-toggle" class="md:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 text-text-color">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
        <!-- Links for large screens -->
        <div class="hidden md:flex space-x-4 items-center">
            @foreach ($classes as $class)
                <a href="{{ route('classe.show', $class->level) }}" class="link {{ request()->is("classe/{$class->level}") ? 'active' : '' }}">{{ $class->name }}</a>
            @endforeach
            @auth
            <a href="{{ route('ds.myDS', Auth::user()->id) }}" class="link {{ request()->routeIs('ds.myDS') ? 'active' : '' }}">Mes devoirs</a>
            
                @if (Auth::user()->role === 'admin')
                <a href="{{ route('students.show') }}" class="link {{ request()->routeIs('students.show') ? 'active' : '' }}">Mes élèves</a>
                    <a href="{{ route('admin') }}" class="bg-blue-500 text-white font-bold text-center rounded-lg p-2 {{ request()->is("admin") ? 'active' : '' }}">admin</a>
                @endif
            @endauth
        </div>
        <div class="hidden md:flex">
            @auth
                <div class="relative" id="profile-dropdown">
                    <button id="profile-toggle" class="flex items-center focus:outline-none">
                            <span class="text-xs mr-2 hover:brightness-50 transition duration-300">{{ Auth::user()->name }}</span>
                        @if (Str::startsWith(Auth::user()->avatar, 'http'))
                            <img src="{{ Auth::user()->avatar }}" class="w-9 h-9 rounded-full border border-black object-cover hover:brightness-50 transition duration-300  "alt="Profile Picture">
                        @else
                            <img src="{{ asset('storage/images/' . Auth::user()->avatar) }}" class="w-9 h-9 rounded-full border border-black object-cover hover:brightness-50 transition duration-300" alt="Profile Picture">
                        @endif
                    </button>
                    <div id="profile-popup" class="popup absolute right-0 mt-2 py-2 w-48 rounded-md shadow-xl z-20 hidden">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm capitalize">Mon profil</a>
                        <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm capitalize" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Se déconnecter</a>
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
    <div id="mobile-menu" class="md:hidden absolute right-1 top-0 mt-14 p-4 rounded-lg shadow-xl bg-gray-100 space-y-4 z-50 hidden">
        @foreach ($classes as $class)
            <a href="{{ route('classe.show', $class->level) }}" class="block link {{ request()->is("classe/{$class->level}") ? 'active' : '' }}">{{ $class->name }}</a>
        @endforeach
        @auth
        <a href="{{ route('ds.myDS', Auth::user()->id) }}" class="block link {{ request()->routeIs('ds.myDS') ? 'active' : '' }}">Mes devoirs</a>
        
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin') }}" class="block bg-yellow-100 rounded-lg p-3 link {{ request()->is("admin") ? 'active' : '' }}">admin</a>
            @endif
            <a href="{{ route('profile.edit') }}" class="block bg-blue-100 rounded-lg p-3 link {{ request()->is("profile") ? 'active' : '' }}">Mon profil</a>
            <a href="{{ route('logout') }}" class="block bg-red-100 rounded-lg p-3 link" onclick="event.preventDefault(); document.getElementById('logout-form-small').submit();">Se déconnecter</a>
        @else
            <a href="{{ route('login') }}" class="block bg-green-100 rounded-lg p-3 link">Se connecter</a>
        @endauth
    </div>
</nav>
@auth
    <form id="logout-form-small" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
@endauth
</header>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var toggleButton = document.getElementById('menu-toggle');
        var mobileMenu = document.getElementById('mobile-menu');
        var profileToggle = document.getElementById('profile-toggle');
        var profilePopup = document.getElementById('profile-popup');

        toggleButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
        if (profileToggle) {
        profileToggle.addEventListener('click', function() {
            profilePopup.classList.toggle('hidden');
        });
    }
    if (profilePopup) {
        document.addEventListener('click', function(event) {
            var isClickInsideProfile = profileToggle.contains(event.target) || profilePopup.contains(event.target);
            if (!isClickInsideProfile) {
                profilePopup.classList.add('hidden');
            }
        });
    }
    });
</script>



        