import type { SortOption, ProblemSort, ExerciseSort, PrivateSort } from '@/types/ui';

// ─── DM Header ────────────────────────────────────────────────────────────────

export const DM_DEFAULT_TITLE = 'Devoir Maison — Mathématiques';
export const DM_DEFAULT_LEVEL = 'Terminale Spécialité';
export const DM_DEFAULT_INSTRUCTIONS =
  'Ce devoir maison est à réaliser à votre rythme, sans contrainte de temps. Soignez votre présentation, espacez vos équations et encadrez vos résultats. La calculatrice est autorisée.\n\nVeuillez envoyer votre copie une fois le devoir terminé.';

// ─── Exercise Picker tabs ─────────────────────────────────────────────────────

export const DM_PICKER_TABS = [
  { key: 'problems', label: 'Problems' },
  { key: 'exercises', label: 'Exercices' },
  { key: 'private', label: 'Privés' },
] as const;

export type DMPickerTab = (typeof DM_PICKER_TABS)[number]['key'];

// ─── Sort ─────────────────────────────────────────────────────────────────────

export const DM_PROBLEM_SORT_OPTIONS: SortOption[] = [
  { by: 'name', label: 'Nom', defaultDir: 'asc', ascLabel: 'A→Z', descLabel: 'Z→A' },
  {
    by: 'difficulty',
    label: 'Difficulté',
    defaultDir: 'asc',
    ascLabel: 'facile',
    descLabel: 'difficile',
  },
  { by: 'year', label: 'Année', defaultDir: 'desc', ascLabel: 'ancien', descLabel: 'récent' },
  { by: 'time', label: 'Durée', defaultDir: 'asc', ascLabel: 'courte', descLabel: 'longue' },
];

export const DM_EXERCISE_SORT_OPTIONS: SortOption[] = [
  { by: 'name', label: 'Nom', defaultDir: 'asc', ascLabel: 'A→Z', descLabel: 'Z→A' },
  {
    by: 'difficulty',
    label: 'Difficulté',
    defaultDir: 'asc',
    ascLabel: 'facile',
    descLabel: 'difficile',
  },
  { by: 'order', label: 'Ordre', defaultDir: 'asc', ascLabel: '1→…', descLabel: '…→1' },
];

export const DM_PRIVATE_SORT_OPTIONS: SortOption[] = [
  { by: 'name', label: 'Nom', defaultDir: 'asc', ascLabel: 'A→Z', descLabel: 'Z→A' },
  {
    by: 'difficulty',
    label: 'Difficulté',
    defaultDir: 'asc',
    ascLabel: 'facile',
    descLabel: 'difficile',
  },
  { by: 'time', label: 'Durée', defaultDir: 'asc', ascLabel: 'courte', descLabel: 'longue' },
  { by: 'created_at', label: 'Date', defaultDir: 'desc', ascLabel: 'ancien', descLabel: 'récent' },
];

export const INITIAL_DM_PROBLEM_SORT: ProblemSort = { by: '', dir: 'asc' };
export const INITIAL_DM_EXERCISE_SORT: ExerciseSort = { by: '', dir: 'asc' };
export const INITIAL_DM_PRIVATE_SORT: PrivateSort = { by: '', dir: 'asc' };
