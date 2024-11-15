@props(['introContent', 'introTitle', 'whoamiContent', 'whoamiTitle', 'whoamiImage'])

<div class="flex flex-col md:flex-row justify-center w-11/12 mx-auto p-6 rounded-lg gap-2 mb-8">
    <div class="flex flex-col w-full md:w-3/4 bg-[#FBF7F0] p-6 rounded-lg">
        <h2 class="text-base font-bold text-center">{{ $introTitle ?? 'Bienvenue sur Maths Manager' }}</h2>
        <p class="mt-4 text-sm">{{ $introContent ?? 'Vous trouverez sur ce site des exercices, des quizz, des fiches récapitulatives de cours sur tous les chapitres des classes de Première et Terminale. Les exercices ne disposant pas de correction, vous aurez la possibilité d’envoyer votre travail afin d’obtenir une correction de ma part. Les quizz sont interactifs et permettent de vérifier que le cours est su. Vous pourrez suivre votre progression durant l’année grâce au système de notation et de suivi des exercices et quizz. Un générateur de DS vous permet de concevoir de manière automatique et aléatoire un contrôle personnalisé en fonction de la difficulté, du temps et des chapitres sélectionnés.' }}</p>

        <p class="mt-4 text-sm">
            <a href="{{ route('login') }}" class="underline font-bold">Connectez-vous</a> et contactez
            <a href="mailto:maxime@mathsmanager.fr" class="underline font-bold">Maxime</a> pour accéder à toutes ces fonctionnalités !
        </p>
    </div>

    <!-- Bloc "Qui suis-je ?" avec titre, contenu et image -->
    <div class="flex flex-col w-full md:w-1/5 bg-[#FBF7F0] p-6 rounded-lg items-center">
        <h2 class="text-base font-bold text-center">{{ $whoamiTitle ?? 'Qui suis-je ?' }}</h2>
        <!-- Contenu du bloc "Qui suis-je ?" -->
        @if ($whoamiImage)
        <img src="{{ asset($whoamiImage) }}" alt="Photo de Maxime" class="w-24 h-24 rounded-full my-4">
        @endif
        <p class="mt-4 text-xs text-center">{{ $whoamiContent ?? 'Après deux années de classes préparatoires MPSI, MP* j’ai intégré l’école d’ingénieur ENSEEIHT. Je suis professeur particulier depuis maintenant 8 années, où j’ai pu aider de nombreux élèves à obtenir leur Baccalauréat et poursuivre leurs études dans le supérieur.' }}</p>
    </div>
</div>
