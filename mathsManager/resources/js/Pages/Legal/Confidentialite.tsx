import { Head } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import LegalLayout from '@/Pages/Legal/Partials/LegalLayout';

const SECTIONS = [
  {
    title: 'Données collectées',
    content:
      'Maths Manager peut collecter les informations nécessaires au fonctionnement du service : identité, email, rôle, avatar, groupes, devoirs, copies envoyées, corrections, notes et notifications.',
  },
  {
    title: 'Utilisation des données',
    content:
      'Ces données servent à fournir le service, organiser le suivi pédagogique, sécuriser les accès, afficher les travaux et permettre les échanges entre élèves et professeurs.',
  },
  {
    title: 'Données élèves',
    content:
      'Les données d’un élève sont accessibles à l’élève, à son professeur et aux administrateurs autorisés lorsque cela est nécessaire au support ou à la maintenance.',
  },
  {
    title: 'Copies, notes et corrections',
    content:
      'Les copies, messages, photos de correction et notes sont stockés afin de permettre le suivi pédagogique et la consultation des retours dans le temps.',
  },
  {
    title: 'Hébergement et sécurité',
    content:
      'Les accès sont protégés par authentification et permissions par rôle. Les données sont hébergées sur l’infrastructure utilisée par Maths Manager.',
  },
  {
    title: 'Droits des utilisateurs',
    content:
      'Chaque utilisateur peut demander l’accès, la correction ou la suppression de ses données lorsque cela est applicable. Pour toute demande, contactez maxime@mathsmanager.fr.',
  },
  {
    title: 'Cookies et mesure d’audience',
    content:
      'Cette version de travail ne prévoit pas de suivi publicitaire. Si des outils de mesure d’audience sont ajoutés, cette page sera mise à jour.',
  },
];

export default function Confidentialite() {
  return (
    <AppLayout>
      <Head title="Confidentialité — Maths Manager" />
      <LegalLayout
        title="Confidentialité"
        subtitle="Version de travail — données personnelles, copies, corrections et droits des utilisateurs."
        eyebrow="Politique de confidentialité"
        sections={SECTIONS}
      />
    </AppLayout>
  );
}
