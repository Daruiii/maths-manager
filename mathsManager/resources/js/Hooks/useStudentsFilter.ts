import { useMemo, useState } from 'react';
import { StudentGroup, User } from '@/types/models';
import { matchesStudent } from '@/Utils/searchUtils';

const PAGE_SIZE = 10;

/**
 * Gère la recherche et la pagination des élèves non groupés + filtrage des groupes.
 * Extrait de Index.tsx pour garder la page sous 200 lignes (REACT_RULES).
 */
export function useStudentsFilter(ungroupedStudents: User[], groups: StudentGroup[]) {
  const [search, setSearch] = useState('');
  const [page, setPage] = useState(1);

  const filteredStudents = useMemo(() => {
    const q = search.toLowerCase().trim();
    if (!q) return ungroupedStudents;
    return ungroupedStudents.filter((s) => matchesStudent(s, q));
  }, [ungroupedStudents, search]);

  const filteredGroups = useMemo(() => {
    const q = search.toLowerCase().trim();
    if (!q) return groups;
    return groups.filter((g) => g.name.toLowerCase().includes(q));
  }, [groups, search]);

  const totalPages = Math.max(1, Math.ceil(filteredStudents.length / PAGE_SIZE));
  const paginatedStudents = filteredStudents.slice((page - 1) * PAGE_SIZE, page * PAGE_SIZE);

  const handleSearchChange = (val: string) => {
    setSearch(val);
    setPage(1);
  };

  return {
    search,
    page,
    setPage,
    filteredStudents,
    filteredGroups,
    paginatedStudents,
    totalPages,
    handleSearchChange,
  };
}
