import type { AssignmentGroup, AssignmentItem } from '@/Pages/Teacher/Assignments/Partials/types';
import { STATUS_PRIORITY } from '@/Pages/Teacher/Assignments/Partials/types';

export function formatBatchDate(date: string | null): string {
  if (!date) return 'Aucune échéance';
  return new Intl.DateTimeFormat('fr-FR', { dateStyle: 'medium' }).format(new Date(date));
}

export function sortAssignmentItems(items: AssignmentItem[]): AssignmentItem[] {
  return [...items].sort(
    (a, b) => (STATUS_PRIORITY[a.status] ?? 5) - (STATUS_PRIORITY[b.status] ?? 5)
  );
}

export function filterAssignmentItems(
  items: AssignmentItem[],
  statusFilter: string | null,
  studentSearch: string
): AssignmentItem[] {
  const search = studentSearch.trim().toLowerCase();

  return items.filter((item) => {
    if (statusFilter && item.status !== statusFilter) return false;
    if (!search) return true;

    const name = item.student
      ? `${item.student.first_name} ${item.student.last_name}`.toLowerCase()
      : '';

    return name.includes(search);
  });
}

export function groupAssignmentItems(items: AssignmentItem[]): AssignmentGroup[] {
  const map = new Map<string, AssignmentGroup>();

  for (const item of items) {
    const group = item.student?.group;
    const key = group ? `g${group.id}` : '__none__';
    if (!map.has(key)) map.set(key, { key, name: group?.name ?? null, items: [] });
    map.get(key)!.items.push(item);
  }

  return sortGroups(Array.from(map.values()));
}

export function getInitialCollapsedGroups(): Set<string> {
  return new Set();
}

function sortGroups(groups: AssignmentGroup[]): AssignmentGroup[] {
  return groups.sort((a, b) => {
    if (a.name === null) return 1;
    if (b.name === null) return -1;
    return a.name.localeCompare(b.name);
  });
}
