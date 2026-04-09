import { useProblemSearch } from '@/Hooks/Builder/useProblemSearch';
import { INITIAL_DM_PROBLEM_SORT } from '@/Constants/dm';

export function useDMProblemSearch() {
  return useProblemSearch('teacher.dm.builder.problems', INITIAL_DM_PROBLEM_SORT);
}
