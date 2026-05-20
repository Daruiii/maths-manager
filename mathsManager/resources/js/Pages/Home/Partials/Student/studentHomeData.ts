import type { FlatItem } from '@/Pages/Home/Partials/AssignmentItem';
import type { HomeActiveAssignment } from '@/types';

const STATUS_PRIORITY: Record<string, number> = {
  ongoing: 0,
  paused: 1,
  not_started: 2,
  sent: 3,
  correction_requested: 4,
};

export function buildSortedItems(
  ds: HomeActiveAssignment[],
  dm: HomeActiveAssignment[],
  td: HomeActiveAssignment[]
): FlatItem[] {
  return [
    ...ds.map((i) => ({ ...i, type: 'ds' as const, href: route('ds.show', i.id) })),
    ...dm.map((i) => ({ ...i, type: 'dm' as const, href: route('dm.show', i.id) })),
    ...td.map((i) => ({ ...i, type: 'td' as const, href: route('td.show', i.id) })),
  ].sort((a, b) => {
    const s = (STATUS_PRIORITY[a.status] ?? 9) - (STATUS_PRIORITY[b.status] ?? 9);
    if (s !== 0) return s;
    if (a.due_date && b.due_date) {
      return (
        new Date(`${a.due_date}T00:00:00`).getTime() - new Date(`${b.due_date}T00:00:00`).getTime()
      );
    }
    return a.due_date ? -1 : b.due_date ? 1 : 0;
  });
}

export function isActionableStatus(status: string): boolean {
  return !['sent', 'correction_requested'].includes(status);
}

export function isOngoingStatus(status: string): boolean {
  return ['ongoing', 'paused'].includes(status);
}
