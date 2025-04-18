@section('styles')
    {{-- flowbite --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
@endsection

<header class="bg-secondary-color text-text-color sticky top-0 z-50 fade-in" id="top">
    <nav class="w-full flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            {{-- <img src="{{ asset('storage/images/professor.png') }}" alt="Logo" class="h-8"> --}}
            <span
                class="self-center text-2xl font-semibold whitespace-nowrap text-black">{{ config('app.name') }}</span>
        </a>
        <div class="flex items-center lg:order-2 space-x-3 lg:space-x-0 rtl:space-x-reverse">
            <!-- User menu -->
            @auth
                <button type="button"
                    class="flex text-sm bg-gray-800 rounded-full lg:me-0 focus:ring-4 focus:ring-gray-300"
                    id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                    data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    @if (Str::startsWith(Auth::user()->avatar, 'http'))
                        <img src="{{ Auth::user()->avatar }}"
                            class="w-9 h-9 rounded-full border border-black object-cover hover:brightness-50 transition duration-300  "alt="Profile Picture">
                    @else
                        <img src="{{ asset('storage/images/' . Auth::user()->avatar) }}"
                            class="w-9 h-9 rounded-full border border-black object-cover hover:brightness-50 transition duration-300"
                            alt="Profile Picture">
                    @endif

                </button>
                <!-- Dropdown menu -->
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow"
                    id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900">{{ Auth::user()->name }}</span>
                        <span class="block text-sm  text-gray-500 truncate">{{ Auth::user()->email }}</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon profil</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Se
                                déconnecter</a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="link font-bold text-center">Se
                    connecter</a>
            @endauth
            <!-- Menu toggle button for small screens -->
            <button data-collapse-toggle="navbar-user" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                aria-controls="navbar-user" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
        </div>
        <div class="items-center justify-center hidden w-full lg:flex lg:w-auto lg:order-1" id="navbar-user">
            <ul
                class="flex flex-col items-center font-medium p-4 lg:p-0 mt-4 border border-gray-100 rounded-lg lg:space-x-8 rtl:space-x-reverse lg:flex-row lg:mt-0 lg:border-0">
                @foreach ($classes as $class)
                    @if (!$class->hidden)
                        <li>
                            <a href="{{ route('classe.show', $class->level) }}"
                                class="link block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700 lg:p-0 {{ request()->is("classe/{$class->level}") ? 'active' : '' }}">{{ $class->name }}</a>
                        </li>
                    @endif
                @endforeach
                @auth
                    @if (Auth::user()->role === 'student')
                        <li class="relative">
                            <a href="{{ route('ds.myDS', Auth::user()->id) }}"
                                class="link block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700 lg:p-0 {{ request()->routeIs('ds.myDS') ? 'active' : '' }}">
                                Mes devoirs
                                @if ($dsNotStarted > 0)
                                    <div class="absolute top-0 right-0 bg-red-500 text-white font-size-xxsmall rounded-full w-3 h-3 flex items-center justify-center transform translate-x-3/4 -translate-y-1/2">
                                        {{ $dsNotStarted }}
                                    </div>
                                @endif
                            </a>
                        </li>
                        <li class="relative">
                            <a href="{{ route('exercises_sheet.myExercisesSheets', Auth::user()->id) }}"
                                class="link block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700 lg:p-0 {{ request()->routeIs('exercises_sheet.myExercisesSheets') ? 'active' : '' }}">
                                Mes fiches d'exercices
                                @if ($exercisesSheetNotStarted > 0)
                                    <div class="absolute top-0 right-0 bg-red-500 text-white font-size-xxsmall rounded-full w-3 h-3 flex items-center justify-center transform translate-x-3/4 -translate-y-1/2">
                                        {{ $exercisesSheetNotStarted }}
                                    </div>
                                @endif
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->role === 'admin')
                        {{-- <li>
                            <a href="{{ route('correctionRequest.myCorrections') }}"
                                class="link block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700 lg:p-0 {{ request()->routeIs('correctionRequest.myCorrections') ? 'active' : '' }}">Mes
                                corrections</a>
                        </li> --}}
                        <li>
                            <a href="{{ route('students.show') }}"
                                class="link block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700 lg:p-0 {{ request()->routeIs('students.show') ? 'active' : '' }}">Mes
                                élèves</a>
                        </li>
                        <a href="{{ route('admin') }}"
                            class="bg-blue-500 text-white font-bold text-center rounded-lg p-2 {{ request()->is('admin') ? 'active' : '' }}">admin</a>
                    @endif
                @else
                    <li>
                        <a href="{{ route('isntValid') }}"
                            class="link block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700 lg:p-0 {{ request()->routeIs('isntValid') ? 'active' : '' }}">Mes
                            devoirs</a>
                    </li>
                    <li>
                        <a href="{{ route('isntValid') }}"
                            class="link block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 lg:hover:bg-transparent lg:hover:text-blue-700 lg:p-0 {{ request()->routeIs('isntValid') ? 'active' : '' }}">Mes
                            fiches d'exercices</a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>
</header>

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('a[href="#top"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
@endsection
