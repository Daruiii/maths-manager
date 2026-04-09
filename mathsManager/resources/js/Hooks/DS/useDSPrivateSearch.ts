import { usePrivateSearch } from '@/Hooks/Builder/usePrivateSearch';
import { INITIAL_PRIVATE_SORT } from '@/Constants/ds';

export function useDSPrivateSearch() {
  return usePrivateSearch('teacher.ds.builder.private', INITIAL_PRIVATE_SORT);
}
