import BatchRow from '@/Pages/Teacher/Bureau/Partials/BatchRow';
import type { BatchBrief, BatchType } from '@/types/api';

interface Props {
  list: BatchBrief[];
  type: BatchType;
  emptyLabel: string;
}

export default function BatchList({ list, type, emptyLabel }: Props) {
  if (list.length === 0) {
    return (
      <div className="flex items-center justify-center py-8 text-text-gray text-xs border border-dashed border-border-color rounded-xl">
        {emptyLabel}
      </div>
    );
  }

  return (
    <div className="flex-1 min-h-0 overflow-y-auto space-y-2 pr-1 h-full">
      {list.map((batch) => (
        <BatchRow key={batch.id} batch={batch} type={type} />
      ))}
    </div>
  );
}
