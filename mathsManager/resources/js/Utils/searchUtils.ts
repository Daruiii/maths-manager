import { User } from '@/types/models';

/**
 * Vérifie si un élève correspond à une requête de recherche.
 * Cherche dans prénom+nom, nom+prénom, et email.
 * Utilisé dans useStudentsFilter et useGroupStudentsFilter.
 */
export function matchesStudent(student: User, query: string): boolean {
  const q = query.toLowerCase().trim();
  if (!q) return true;

  const fullName = `${student.first_name} ${student.last_name}`.toLowerCase();
  const fullNameReversed = `${student.last_name} ${student.first_name}`.toLowerCase();

  return (
    fullName.includes(q) || fullNameReversed.includes(q) || student.email.toLowerCase().includes(q)
  );
}
