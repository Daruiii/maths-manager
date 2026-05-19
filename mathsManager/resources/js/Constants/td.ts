// ─── TD Header ────────────────────────────────────────────────────────────────

export const TD_DEFAULT_TITLE = "Fiche d'exercices";
export const TD_DEFAULT_LEVEL = 'Terminale Spécialité';
export const TD_DEFAULT_INSTRUCTIONS = 'Mathématiques';

// ─── Exercise Picker tabs ─────────────────────────────────────────────────────

export const TD_PICKER_TABS = [
  { key: 'exercises', label: 'Exercices' },
  { key: 'private', label: 'Privés' },
] as const;

export type TDPickerTab = (typeof TD_PICKER_TABS)[number]['key'];
