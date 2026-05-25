import type { BatchBrief, BatchType } from '@/types/api';
import type { BatchLists } from '@/Pages/Teacher/Bureau/Partials/bureauDevoirsTypes';

export function buildBatchLists(
  dsBatches: BatchBrief[],
  dmBatches: BatchBrief[],
  tdBatches: BatchBrief[],
  archived: boolean
): BatchLists {
  return {
    ds: dsBatches.filter((batch) => batch.is_archived === archived),
    dm: dmBatches.filter((batch) => batch.is_archived === archived),
    td: tdBatches.filter((batch) => batch.is_archived === archived),
  };
}

export function countBatches(lists: BatchLists): number {
  return lists.ds.length + lists.dm.length + lists.td.length;
}

export function countPendingActions(lists: BatchLists): number {
  return [...lists.ds, ...lists.dm, ...lists.td].reduce(
    (total, batch) => total + batch.pending_actions,
    0
  );
}

export function filterBatchLists(
  lists: BatchLists,
  search: string,
  groupId: number | null,
  pendingOnly: boolean
): BatchLists {
  return {
    ds: filterList(lists.ds, search, groupId, pendingOnly),
    dm: filterList(lists.dm, search, groupId, pendingOnly),
    td: filterList(lists.td, search, groupId, pendingOnly),
  };
}

export function areBatchListsEmpty(lists: BatchLists): boolean {
  return (Object.keys(lists) as BatchType[]).every((type) => lists[type].length === 0);
}

function filterList(
  list: BatchBrief[],
  search: string,
  groupId: number | null,
  pendingOnly: boolean
): BatchBrief[] {
  const query = search.trim().toLowerCase();

  return list.filter((batch) => {
    if (query && !batch.title.toLowerCase().includes(query)) return false;
    if (groupId !== null && !batch.group_ids.includes(groupId)) return false;
    if (pendingOnly && batch.pending_actions <= 0) return false;
    return true;
  });
}
