@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="quote-box">
            <p class="quote-text">“L'imagination est plus importante que le savoir”</p>
            <p class="quote-author">(Albert Einstein)</p>
        </div>
        @auth
            <div class="flex flex-col md:flex-row justify-center w-11/12 mx-auto p-6 rounded-lg gap-2 mb-8 ">
                <div class="flex flex-col w-full md:w-full p-6 rounded-lg">
                    <h2 class="text-base font-bold text-center">Tableau de bord</h2>
                    <div class="flex justify-center flex-wrap gap-4 mt-4">
                        @if (Auth::user()->role == 'admin')
                            <div
                                class="flex flex-col justify-start bg-white border-2 border-gray-200 items-start p-4 w-full md:w-1/2 flex-grow rounded-lg">
                                <div class="w-full flex justify-start items-center">
                                    <h2 class="text-lg leading-6 font-medium text-gray-900">Mes corrections</h2>
                                </div>
                                <div class="flex w-full justify-between items-center py-3 flex-wrap gap-2">
                                    {{-- search bar --}}
                                    <x-search-bar-admin action="{{ route('home') }}" placeholder="Rechercher un élève ..."
                                        name="search" />
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
                                                                class="w-12 h-12 rounded-full border border-black object-cover hover:brightness-50 transition duration-300  "alt="Profile Picture">
                                                        @else
                                                            <img src="{{ asset('storage/images/' . $correctionRequest->user->avatar) }}"
                                                                class="w-12 h-12 rounded-full border border-black object-cover hover:brightness-50 transition duration-300"
                                                                alt="Profile Picture">
                                                        @endif
                                                    </div>
                                                    <p class="text-body truncate">{{ $correctionRequest->user->name }}</p>
                                                    <p class="text-title text-center text-sm"> <a
                                                            href="{{ route('ds.show', $correctionRequest->ds_id) }}"
                                                            class="text-indigo-600 hover:text-indigo-900 underline">
                                                            DS n°{{ $correctionRequest->ds_id }}
                                                        </a></p>
                                                    <div
                                                        class="correction-card-score {{ $correctionRequest->status == 'pending' ? 'score-low' : 'score-high' }}">
                                                        <p class="text-white text-center">
                                                            {{ ucfirst($correctionRequest->status) }}</p>
                                                    </div>
                                                </div>
                                                <button class="correction-card-button"
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
                                    {{ $correctionRequests->links('vendor.pagination.simple-tailwind') }}
                                </div>
                            </div>
                            <div
                                class="flex flex-col justify-start items-center p-4 bg-white border-2 border-gray-200 rounded-md max-w-full min-w-80 max-h-96">
                                <!-- Your DS data goes here -->
                                <h2 class="text-base leading-6 font-medium text-gray-900">Devoirs </h2>
                                <a href="{{ route('ds.index') }}"
                                    class="text-xs text-black hover:text-indigo-900 underline">Voir en détail</a>
                                <div class="overflow-y-auto w-11/12 h-full bg-gray-100 rounded-md p-3 mt-2 max-h-96">
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
                        @else
                            <div class="flex flex-col w-full md:w-8/12 bg-[#F0EAD6] p-4 rounded-lg">
                                <h3 class="text-sm font-bold">Devoirs</h3>
                                <div class="flex-row flex justify-between">
                                    <div class="flex flex-col gap-2 mt-2 bg-gray-200 p-2 rounded w-2/3">
                                        <p class="text-xs">Total : {{ $totalDS ?? 'N/A' }}</p>
                                        <div class="bg-[#019875] p-2 rounded">
                                            <p class="text-xs">À faire : {{ $notStartedDS ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-[#fda054] p-2 rounded">
                                            <p class="text-xs">En cours : {{ $inProgressDS ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-[#318CE7] p-2 rounded">
                                            <p class="text-xs">Envoyés : {{ $sentDS ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-red-200 p-2 rounded">
                                            <p class="text-xs">Corrigés : {{ $correctedDS ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2 mt-2 p-2 rounded w-1/2 justify-between items-center">
                                        <div class="bg-gray-200 p-2 rounded flex flex-col justify-center items-center w-full">
                                            <p class="text-xs mb-2">Moyenne </p>
                                            <p class="text-xl font-bold">{{ $averageGrade ?? 'N/A' }}</p>
                                            <div class="w-1/6 h-0.5 bg-black"></div>
                                            <p class="text-xl font-bold">20 </p>
                                        </div>
                                        <x-btn-see href="{{ route('ds.myDS', Auth::user()->id) }}">
                                            {{ __('Voir mes devoirs') }}
                                        </x-btn-see>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col w-full md:w-56 bg-[#F0EAD6] p-4 rounded-lg">
                                <h3 class="text-sm font-bold">Quizz (10 derniers)</h3>
                                <p class="text-xs">Résultat moyen : {{ $scores ?? 'N/A' }} / 10</p>
                                <div class="flex flex-col gap-2 mt-2 p-2 rounded w-full justify-between items-center">
                                    <x-progress-circle goodAnswers="{{ isset($goodAnswers) ? $goodAnswers : '50' }}"
                                        badAnswers="{{ isset($badAnswers) ? $badAnswers : '50' }}" />
                                </div>
                            </div>
                            {{-- <div class="flex flex-col w-full md:w-56 bg-[#F0EAD6] p-4 rounded-lg">
                            <h3 class="text-sm font-bold">Exercices</h3>
                            <p class="text-xs">Vous avez réalisé x exercices sur un total de x.</p>
                        </div>
                        <div class="flex flex-col w-full md:w-1/2 bg-[#F0EAD6] p-4 rounded-lg">
                            <h3 class="text-sm font-bold">Progression</h3>
                            <p class="text-xs">Votre progression est de %.</p>
                        </div> --}}
                        @endif
                    </div>
                </div>
                @if (Auth::user()->role !== 'admin')
                    <div class="flex flex-col w-full md:w-1/5 bg-[#FBF7F0] p-6 rounded-lg">
                        <h2 class="text-base font-bold text-center">Qui suis-je ?</h2>
                        <p class="mt-4 text-xs">Après deux années de classes
                            préparatoires MPSI, MP*
                            j’ai intégré l’école d’ingénieur
                            ENSEEIHT.
                            Je suis professeur particulier depuis maintenant 8 années, où j’ai pu aider de nombreux élèves à
                            obtenir
                            leur Baccalauréat et poursuivre leurs études dans le supérieur.
                        </p>
                    </div>
                @endif
            @endauth
            @if (!Auth::check())
                <div class="flex flex-col md:flex-row justify-center w-11/12 mx-auto p-6 rounded-lg gap-2 mb-8 ">

                    <div class="flex flex-col w-full md:w-3/4 bg-[#FBF7F0] p-6 rounded-lg">
                        <h2 class="text-base font-bold text-center">Bienvenue sur Maths Manager</h2>
                        <p class="mt-4 text-sm">Vous trouverez sur ce site des exercices, des quizz, des fiches
                            récapitulatives de cours sur
                            tous les chapitres des classes de Première et Terminale.
                            Les exercices ne disposant pas de correction, vous aurez la possibilité d’envoyer
                            votre travail afin d’obtenir une correction de ma part.
                            Les quizz sont interactifs et permettent de vérifier que le cours est su.
                            Vous pourrez suivre votre progression durant l’année grâce au système de notation et
                            de suivi des exercices et quizz.
                            Un générateur de DS vous permet de concevoir de manière automatique et aléatoire
                            un contrôle personnalisé en fonction de la difficulté, du temps et des chapitres
                            sélectionnés.</p>
                        <p class="mt-4 text-sm"><a href="{{ route('login') }}" class="underline font-bold">Connectez-vous
                            </a> et contactez <a href="mailto:maxime@mathsmanager.fr"
                                class="underline font-bold">Maxime</a>
                            pour accéder
                            à toutes ces fonctionnalités !</p>
                    </div>
                    <div class="flex flex-col w-full md:w-1/5 bg-[#FBF7F0] p-6 rounded-lg">
                        <h2 class="text-base font-bold text-center">Qui suis-je ?</h2>
                        <p class="mt-4 text-xs">Après deux années de classes
                            préparatoires MPSI, MP*
                            j’ai intégré l’école d’ingénieur
                            ENSEEIHT.
                            Je suis professeur particulier depuis maintenant 8 années, où j’ai pu aider de nombreux élèves à
                            obtenir
                            leur Baccalauréat et poursuivre leurs études dans le supérieur.
                        </p>
                    </div>
                </div>
            @endif
        </div>
        {{-- cookies --}}
        {{-- <x-cookies /> --}}
    @endsection
