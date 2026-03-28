import { useMemo, useState } from 'react';
import { StudentGroup, User } from '@/types/models';
import { matchesStudent } from '@/Utils/searchUtils';

const PAGE_SIZE = 10;

/**
 * Gère la recherche et la pagination des élèves non groupés + filtrage des groupes.
 * Quand une recherche est active, inclut aussi les élèves dans des groupes qui correspondent.
 */
export function useStudentsFilter(ungroupedStudents: User[], groups: StudentGroup[]) {
  const [search, setSearch] = useState('');
  const [page, setPage] = useState(1);

  const q = search.toLowerCase().trim();

  const filteredStudents = useMemo(() => {
    if (!q) return ungroupedStudents;
    return ungroupedStudents.filter((s) => matchesStudent(s, q));
  }, [ungroupedStudents, q]);

  const filteredGroups = useMemo(() => {
    if (!q) return groups;
    return groups.filter((g) => g.name.toLowerCase().includes(q));
  }, [groups, q]);

  const matchedGroupedStudents = useMemo(() => {
    if (!q) return [];
    const results: User[] = [];
    for (const group of groups) {
      if (!group.students) continue;
      for (const student of group.students) {
        if (matchesStudent(student, q)) results.push(student);
      }
    }
    return results;
  }, [groups, q]);

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
    matchedGroupedStudents,
    paginatedStudents,
    totalPages,
    handleSearchChange,
  };
}
