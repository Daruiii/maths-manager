import { useProblemSearch } from '@/Hooks/Builder/useProblemSearch';
import { INITIAL_PROBLEM_SORT } from '@/Constants/ds';

export function useDSProblemSearch() {
  return useProblemSearch('teacher.ds.builder.problems', INITIAL_PROBLEM_SORT);
}
