import { useExerciseSearch } from '@/Hooks/Builder/useExerciseSearch';
import { INITIAL_DM_EXERCISE_SORT } from '@/Constants/dm';

export function useDMExerciseSearch() {
  return useExerciseSearch('teacher.dm.builder.exercises', INITIAL_DM_EXERCISE_SORT);
}
