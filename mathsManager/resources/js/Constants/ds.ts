import type { SortOption, ProblemSort, ExerciseSort, PrivateSort } from '@/types/ui';

// ─── DS Header ────────────────────────────────────────────────────────────────

export const DS_DEFAULT_TITLE = 'Sujet de DS — Mathématiques';
export const DS_DEFAULT_LEVEL = 'Terminale Spécialité';
export const DS_DEFAULT_INSTRUCTIONS =
  "Ce sujet est une simulation de devoir surveillé de difficulté moyenne (niveau bac) pour vous aider à maîtriser la gestion du temps et tester vos connaissances. Merci de traiter ce devoir avec sérieux, en respectant le temps imparti et en soignant votre présentation. N'oubliez pas d'espacer vos équations et d'encadrer vos résultats. La calculatrice est autorisée.\n\nVeuillez envoyer votre copie à la fin pour correction.";

// ─── Exercise Picker tabs ─────────────────────────────────────────────────────

export const PICKER_TABS = [
  { key: 'problems', label: 'Problems' },
  { key: 'exercises', label: 'Exercices' },
  { key: 'private', label: 'Privés' },
] as const;

export type PickerTab = (typeof PICKER_TABS)[number]['key'];

// ─── Sort ─────────────────────────────────────────────────────────────────────

export const PROBLEM_SORT_OPTIONS: SortOption[] = [
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

export const EXERCISE_SORT_OPTIONS: SortOption[] = [
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

export const PRIVATE_SORT_OPTIONS: SortOption[] = [
  { by: 'name', label: 'Nom', defaultDir: 'asc', ascLabel: 'A→Z', descLabel: 'Z→A' },
  {
    by: 'difficulty',
    label: 'Difficulté',
    defaultDir: 'asc',
    ascLabel: 'facile',
    descLabel: 'difficile',
  },
  { by: 'time', label: 'Durée', defaultDir: 'asc', ascLabel: 'courte', descLabel: 'longue' },
  {
    by: 'created_at',
    label: 'Date',
    defaultDir: 'desc',
    ascLabel: 'ancien',
    descLabel: 'récent',
  },
];

export const INITIAL_PROBLEM_SORT: ProblemSort = { by: '', dir: 'asc' };
export const INITIAL_EXERCISE_SORT: ExerciseSort = { by: '', dir: 'asc' };
export const INITIAL_PRIVATE_SORT: PrivateSort = { by: '', dir: 'asc' };
