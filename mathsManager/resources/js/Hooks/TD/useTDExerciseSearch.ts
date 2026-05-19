import { useExerciseSearch } from '@/Hooks/Builder/useExerciseSearch';
import { INITIAL_EXERCISE_SORT } from '@/Constants/ds';

export function useTDExerciseSearch() {
  return useExerciseSearch('teacher.td.builder.exercises', INITIAL_EXERCISE_SORT);
}
