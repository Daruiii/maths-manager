import type { FlatItem } from '@/Pages/Home/Partials/AssignmentItem';
import type { HomeActiveAssignment } from '@/types';

const STATUS_PRIORITY: Record<string, number> = {
  ongoing: 0,
  paused: 1,
  finished: 2,
  finished_late: 2,
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

const NON_ACTIONABLE = new Set([
  'sent',
  'correction_requested',
  'corrected',
  'correction_unlocked',
]);

export function isActionableStatus(status: string): boolean {
  return !NON_ACTIONABLE.has(status);
}

export function isOngoingStatus(status: string): boolean {
  return ['ongoing', 'paused'].includes(status);
}

export function studentAssignmentCtaLabel(status?: string): string {
  if (!status) return 'Voir mes devoirs';
  if (isOngoingStatus(status)) return 'Reprendre';
  if (status === 'finished' || status === 'finished_late') return 'Envoyer ma copie';
  if (status === 'not_started') return 'Commencer';
  return 'Ouvrir';
}
