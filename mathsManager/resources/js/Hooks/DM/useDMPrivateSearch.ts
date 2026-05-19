import { usePrivateSearch } from '@/Hooks/Builder/usePrivateSearch';
import { INITIAL_DM_PRIVATE_SORT } from '@/Constants/dm';

export function useDMPrivateSearch() {
  return usePrivateSearch('teacher.dm.builder.private', INITIAL_DM_PRIVATE_SORT);
}
