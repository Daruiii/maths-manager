import { useMemo, useState } from 'react';
import { User } from '@/types/models';
import { matchesStudent } from '@/Utils/searchUtils';

const PAGE_SIZE = 20;

/**
 * Gère la recherche et la pagination des élèves d'un groupe.
 * Utilisé dans Group.tsx.
 */
export function useGroupStudentsFilter(students: User[]) {
  const [search, setSearch] = useState('');
  const [page, setPage] = useState(1);

  const filteredStudents = useMemo(() => {
    const q = search.toLowerCase().trim();
    if (!q) return students;
    return students.filter((s) => matchesStudent(s, q));
  }, [students, search]);

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
    paginatedStudents,
    totalPages,
    handleSearchChange,
  };
}
