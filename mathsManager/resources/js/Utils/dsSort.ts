import type { SortOption } from '@/types/ui';

/**
 * Logique de toggle cyclique pour le tri dans le DS picker :
 * - Champ différent      → active avec defaultDir
 * - Champ actif + defaultDir → inverse la direction
 * - Champ actif + inversé   → désactive (by = '')
 */
export function getNextSort(
  opt: SortOption,
  current: { by: string; dir: 'asc' | 'desc' }
): { by: string; dir: 'asc' | 'desc' } {
  if (current.by !== opt.by) {
    return { by: opt.by, dir: opt.defaultDir };
  }
  const oppositeDir: 'asc' | 'desc' = opt.defaultDir === 'asc' ? 'desc' : 'asc';
  if (current.dir === opt.defaultDir) {
    return { by: opt.by, dir: oppositeDir };
  }
  return { by: '', dir: 'asc' };
}
