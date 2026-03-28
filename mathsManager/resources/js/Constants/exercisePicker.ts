export const DIFFICULTY_OPTIONS = [
  { label: 'Toutes', value: '' },
  { label: '★', value: '1' },
  { label: '★★', value: '2' },
  { label: '★★★', value: '3' },
  { label: '★★★★', value: '4' },
  { label: '★★★★★', value: '5' },
];

export const getDifficultyLabel = (value: string) =>
  DIFFICULTY_OPTIONS.find((opt) => opt.value === value)?.label;
