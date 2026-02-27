<x-mail::message>
# Bonjour {{ $user->first_name }},

Suite à l'étude de votre candidature pour devenir professeur sur Maths Manager, nous avons le plaisir de vous informer que votre profil a retenu notre attention.

Afin de finaliser votre intégration, nous vous invitons à planifier un court entretien visio avec notre équipe. Cela nous permettra de faire connaissance et de vous présenter le fonctionnement de la plateforme.

Veuillez choisir un créneau qui vous convient en cliquant sur le bouton ci-dessous :
<!-- TODO: Add calendly link (the real one)-->

<x-mail::button :url="'https://calendly.com/davidmeguira6/30min'">
Planifier l'entretien
</x-mail::button>

À très vite !

L'équipe **{{ config('app.name') }}**
</x-mail::message>
