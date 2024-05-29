@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="quote-box">
            <p class="quote-text">“L'imagination est plus importante que le savoir”</p>
            <p class="quote-author">(Albert Einstein)</p>
        </div>
        <div class="flex flex-col md:flex-row justify-center w-11/12 mx-auto p-6 rounded-lg gap-2 mb-8 ">
            @if (Auth::check())
                <div class="flex flex-col w-full md:w-3/4 bg-[#FBF7F0] p-6 rounded-lg">
                    <h2 class="text-base font-bold text-center">Tableau de bord</h2>
                    <div class="flex justify-center flex-wrap gap-4 mt-4">
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
                            <p class="text-xs">Résultat moyen : {{$scores ?? 'N/A' }} / 10</p>
                            <div class="flex flex-col gap-2 mt-2 p-2 rounded w-full justify-between items-center">
                                <x-progress-circle goodAnswers={{$goodAnswers}} badAnswers={{$badAnswers}} />
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
                    </div>
                </div>
            @else
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
                        </a> et contactez <a href="mailto:maxime@mathsmanager.fr" class="underline font-bold">Maxime</a>
                        pour accéder
                        à toutes ces fonctionnalités !</p>
                </div>
            @endif
            <div class="flex flex-col w-full md:w-1/5 bg-[#FBF7F0] p-6 rounded-lg">
                <h2 class="text-base font-bold text-center">Qui suis-je ?</h2>
                <p class="mt-4 text-xs">Après deux années de classes
                    préparatoires MPSI, MP*
                    j’ai intégré l’école d’ingénieur
                    ENSEEIHT.
                    Je suis professeur particulier depuis maintenant 8 années, où j’ai pu aider de nombreux élèves à obtenir
                    leur Baccalauréat et poursuivre leurs études dans le supérieur.
                </p>
            </div>
        </div>
        {{-- cookies --}}
        {{-- <x-cookies /> --}}
    @endsection
