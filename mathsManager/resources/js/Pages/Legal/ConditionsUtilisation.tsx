import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import LegalLayout from '@/Pages/Legal/Partials/LegalLayout';

const SECTIONS = [
  {
    title: 'Objet',
    content:
      'Maths Manager permet aux élèves et aux professeurs d’organiser le travail en mathématiques : exercices, DS, DM, TD, copies, corrections, ressources et suivi pédagogique.',
  },
  {
    title: 'Accès au service',
    content:
      'L’accès à certaines fonctionnalités nécessite un compte. Chaque utilisateur s’engage à fournir des informations exactes et à conserver ses identifiants confidentiels.',
  },
  {
    title: 'Contenus pédagogiques Maths Manager',
    content:
      'Les exercices, problèmes, corrigés, fiches, quiz et ressources issus de la bibliothèque globale Maths Manager sont protégés. Ils sont fournis pour une utilisation au sein de la plateforme : consultation, entraînement, assignation et suivi pédagogique.',
  },
  {
    title: 'Interdictions',
    content:
      'Toute extraction massive, reproduction, redistribution, publication externe, revente ou réutilisation hors de Maths Manager des contenus pédagogiques globaux est interdite sans autorisation écrite préalable.',
  },
  {
    title: 'Contenus privés des professeurs',
    content:
      'Les contenus créés par un professeur dans son espace privé restent sa propriété. Maths Manager les héberge uniquement pour permettre leur utilisation dans la plateforme.',
  },
  {
    title: 'Copies et corrections',
    content:
      'Les copies envoyées par les élèves, les messages et les corrections sont utilisés pour assurer le suivi pédagogique entre l’élève et le professeur concerné.',
  },
  {
    title: 'Évolution des conditions',
    content:
      'Ces conditions pourront évoluer avec le service. Une version plus complète sera publiée au fur et à mesure de la stabilisation de Maths Manager.',
  },
];

export default function ConditionsUtilisation() {
  return (
    <AppLayout>
      <Head title="Conditions d’utilisation — Maths Manager" />
      <LegalLayout
        title="Conditions d’utilisation"
        subtitle="Version de travail — règles d’usage de Maths Manager et protection des contenus pédagogiques."
        eyebrow="Conditions d’utilisation"
        sections={SECTIONS}
      />
    </AppLayout>
  );
}
