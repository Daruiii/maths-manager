@props(['introContent', 'introTitle', 'whoamiContent', 'whoamiTitle', 'whoamiImage'])

<div class="flex flex-col items-center w-full mx-auto p-6 space-y-10">
    
    <!-- Conteneur principal en deux colonnes pour écrans larges -->
    <div class="flex flex-col lg:flex-row lg:space-x-10 w-full lg:w-3/4 mx-auto">
        
        <!-- Bloc de Bienvenue à gauche -->
        <div class="flex flex-col w-full lg:w-2/3 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl lg:text-2xl font-bold text-blue-700 text-center lg:text-left">{{ $introTitle ?? 'Bienvenue sur Maths Manager' }}</h2>
            <p class="mt-4 text-gray-700 text-sm lg:text-base leading-relaxed">
                {{ $introContent ?? 'Vous trouverez sur ce site des exercices, des quizz, des fiches récapitulatives de cours sur tous les chapitres des classes de Première et Terminale. Les exercices ne disposant pas de correction, vous aurez la possibilité d’envoyer votre travail afin d’obtenir une correction de ma part. Les quizz sont interactifs et permettent de vérifier que le cours est su. Vous pourrez suivre votre progression durant l’année grâce au système de notation et de suivi des exercices et quizz. Un générateur de DS vous permet de concevoir de manière automatique et aléatoire un contrôle personnalisé en fonction de la difficulté, du temps et des chapitres sélectionnés.' }}
            </p>
            <p class="mt-4 text-sm">
                <a href="{{ route('login') }}" class="text-indigo-500 hover:text-indigo-700 font-semibold underline">Connectez-vous</a> et contactez
                <a href="mailto:maxime@mathsmanager.fr" class="text-indigo-500 hover:text-indigo-700 font-semibold underline">Maxime</a> pour accéder à toutes ces fonctionnalités !
            </p>

            <!-- Bloc Vidéo de Présentation sous le Bienvenue -->
            <div class="mt-6 aspect-w-16 aspect-h-9 shadow-lg rounded-lg overflow-hidden">
                <iframe class="w-full h-full" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>

        <!-- Bloc "Qui suis-je ?" à droite avec hauteur fixe et barre de défilement -->
        <div class="flex flex-col items-center w-full lg:w-1/3 bg-white p-6 rounded-lg shadow-md mt-10 lg:mt-0 max-h-fit overflow-y-auto">
            <h2 class="text-xl font-bold text-blue-700 text-center">{{ $whoamiTitle ?? 'Qui suis-je ?' }}</h2>
            @if ($whoamiImage)
                <img src="{{ asset($whoamiImage) }}" alt="Photo de Maxime" class="w-24 h-24 rounded-full my-4 shadow-md">
            @endif
            <p class="text-sm text-gray-600 text-center mt-4">
                {{ $whoamiContent ?? 'Après deux années de classes préparatoires MPSI, MP*, j’ai intégré l’école d’ingénieur ENSEEIHT. Je suis professeur particulier depuis maintenant 8 années, où j’ai pu aider de nombreux élèves à obtenir leur Baccalauréat et poursuivre leurs études dans le supérieur.' }}
            </p>
        </div>
    </div>
</div>
