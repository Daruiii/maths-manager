import { usePrivateSearch } from '@/Hooks/Builder/usePrivateSearch';
import { INITIAL_PRIVATE_SORT } from '@/Constants/ds';

export function useTDPrivateSearch() {
  return usePrivateSearch('teacher.td.builder.private', INITIAL_PRIVATE_SORT);
}
