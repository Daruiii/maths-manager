import type { BatchGroup } from '@/Pages/Home/Partials/PendingFeedItems';
import type { HomePendingCorrectionItem } from '@/types';
import type { BatchType } from '@/types/api';

export const BATCH_SHORTCUTS: { type: BatchType; label: string }[] = [
  { type: 'ds', label: 'DS' },
  { type: 'dm', label: 'DM' },
  { type: 'td', label: 'TD' },
];

export function groupByBatch(items: HomePendingCorrectionItem[]): BatchGroup[] {
  const map = new Map<string, BatchGroup>();

  for (const item of items) {
    const key = item.batch_id ? `${item.subject_type}::${item.batch_id}` : `correction::${item.id}`;

    if (!map.has(key)) {
      map.set(key, {
        key,
        title: item.subject_title,
        type: item.subject_type,
        href: item.batch_url ?? route('teacher.corrections.show', item.id),
        items: [],
      });
    }

    map.get(key)!.items.push(item);
  }

  return Array.from(map.values());
}

export function teacherHeroMessage(corrections: number, unlocks: number): string {
  if (corrections + unlocks === 0) return 'Tout est à jour.';
  if (corrections === 1 && unlocks === 0) return 'Une copie attend ta correction.';
  if (corrections > 1 && unlocks === 0) return `${corrections} copies attendent ta correction.`;
  if (unlocks > 0 && corrections === 0) {
    return `${unlocks} déblocage${unlocks > 1 ? 's' : ''} en attente.`;
  }
  return `${corrections} copies · ${unlocks} déblocage${unlocks > 1 ? 's' : ''}.`;
}
