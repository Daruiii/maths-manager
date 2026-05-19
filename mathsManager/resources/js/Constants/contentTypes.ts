import { BookOpen, FileText, Sparkles, FileEdit } from 'lucide-react';

export const CONTENT_TYPE_META = {
  ds: {
    label: 'DS',
    createLabel: 'Créer un DS',
    groupLabel: 'DS groupe',
    createForGroupLabel: 'Créer un DS pour ce groupe',
    savedTitle: 'DS sauvegardés',
    savedSubtitle: 'Retrouvez vos devoirs surveillés enregistrés',
    icon: BookOpen,
  },
  td: {
    label: 'TD',
    createLabel: 'Créer un TD',
    groupLabel: 'Fiche groupe',
    createForGroupLabel: 'Créer un TD pour ce groupe',
    savedTitle: 'TD sauvegardés',
    savedSubtitle: 'Retrouvez vos fiches de travaux dirigés',
    icon: FileText,
  },
  dm: {
    label: 'DM',
    createLabel: 'Créer un DM',
    groupLabel: 'DM groupe',
    createForGroupLabel: 'Créer un DM pour ce groupe',
    savedTitle: 'DM sauvegardés',
    savedSubtitle: 'Retrouvez vos devoirs maison enregistrés',
    icon: Sparkles,
  },
} as const;

export type ContentTypeKey = keyof typeof CONTENT_TYPE_META;

export const CONTENT_ITEM_META = {
  problem: {
    label: 'Problem',
    shortLabel: 'Pb',
    icon: FileText,
  },
  exercise: {
    label: 'Exercice',
    shortLabel: 'Ex',
    icon: BookOpen,
  },
  private: {
    label: 'Exercice privé',
    shortLabel: 'Privé',
    icon: FileEdit,
  },
} as const;

export type ContentItemKey = keyof typeof CONTENT_ITEM_META;

// ─── Type badge styles — utilisés pour les badges DS/DM/TD dans les listes ───
// Couleurs par type de contenu (pas par rôle)

export const CONTENT_TYPE_STYLES = {
  ds: {
    badge: 'bg-tertiary-color/15 text-tertiary-color',
    dot: 'bg-tertiary-color',
  },
  dm: {
    badge: 'bg-admin-color/15 text-admin-color',
    dot: 'bg-admin-color',
  },
  td: {
    badge: 'bg-info-color/15 text-info-color',
    dot: 'bg-info-color',
  },
} as const;

export type ContentTypeKey3 = keyof typeof CONTENT_TYPE_STYLES;
