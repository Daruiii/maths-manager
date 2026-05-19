import { useExerciseSearch } from '@/Hooks/Builder/useExerciseSearch';
import { INITIAL_EXERCISE_SORT } from '@/Constants/ds';

export function useDSExerciseSearch() {
  return useExerciseSearch('teacher.ds.builder.exercises', INITIAL_EXERCISE_SORT);
}
