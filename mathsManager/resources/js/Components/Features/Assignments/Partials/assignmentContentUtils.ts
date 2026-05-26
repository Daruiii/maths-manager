import type { AssignmentListItem } from '@/types/models';
import { normalizeStoragePath } from '@/Utils/pickableItemContent';

export interface AssignmentContentItem extends AssignmentListItem {
  kind: string;
}

export function buildAssignmentContentItems(
  problems: AssignmentListItem[],
  exercises: AssignmentListItem[],
  privateExercises: AssignmentListItem[]
): AssignmentContentItem[] {
  return [
    ...problems.map((item) => ({ ...item, kind: 'Problème' })),
    ...exercises.map((item) => ({ ...item, kind: 'Exercice' })),
    ...privateExercises.map((item) => ({ ...item, kind: 'Exercice privé' })),
  ];
}

export function itemLabel(item: AssignmentListItem): string {
  return item.title ?? item.name ?? `#${item.id}`;
}

export function imageMap(item: AssignmentListItem): Record<string, string> {
  if (!item.image_paths) return {};

  let raw: Record<string, string>;

  if (typeof item.image_paths === 'string') {
    try {
      raw = JSON.parse(item.image_paths) as Record<string, string>;
    } catch {
      return {};
    }
  } else {
    raw = item.image_paths;
  }

  return Object.fromEntries(
    Object.entries(raw).map(([key, value]) => [key, normalizeStoragePath(value)])
  );
}
