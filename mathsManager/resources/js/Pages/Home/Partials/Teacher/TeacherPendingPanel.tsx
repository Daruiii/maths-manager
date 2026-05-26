import { Link } from '@inertiajs/react';
import { CheckCircle2, ChevronRight } from 'lucide-react';
import {
  BatchCorrectionItem,
  BatchUnlockItem,
  type BatchGroup,
} from '@/Pages/Home/Partials/PendingFeedItems';
import type { HomeUnlockRequestItem } from '@/types';

interface Props {
  allClear: boolean;
  batches: BatchGroup[];
  unlockRequests: HomeUnlockRequestItem[];
}

export default function TeacherPendingPanel({ allClear, batches, unlockRequests }: Props) {
  if (allClear) {
    return (
      <div className="flex flex-col items-center gap-4 py-8 text-center bg-secondary-color border border-border-color rounded-2xl animate-fadeInUp">
        <div className="w-12 h-12 rounded-2xl bg-success-color/10 border border-success-color/20 flex items-center justify-center">
          <CheckCircle2 size={22} className="text-success-color" />
        </div>
        <div>
          <p className="text-sm font-comfortaa-bold text-text-color">Tout est traité</p>
          <p className="text-xs text-text-gray mt-1">Prochaine étape dans ton bureau.</p>
        </div>
      </div>
    );
  }

  const displayTotal = batches.length + unlockRequests.length;

  return (
    <div className="bg-secondary-color border border-border-color rounded-2xl overflow-hidden">
      <div className="flex items-center justify-between px-4 py-3 border-b border-border-color">
        <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
          À traiter
        </span>
        <span className="text-xs text-text-gray bg-surface-color border border-border-color px-2 py-0.5 rounded-full">
          {displayTotal}
        </span>
      </div>
      <div className="p-2 space-y-0.5 max-h-[340px] overflow-y-auto">
        {batches.map((batch, i) => (
          <BatchCorrectionItem key={batch.key} batch={batch} index={i} compact />
        ))}
        {unlockRequests.map((item, i) => (
          <BatchUnlockItem
            key={item.batch_id ?? i}
            item={item}
            index={batches.length + i}
            compact
          />
        ))}
      </div>
      <div className="px-4 py-2.5 border-t border-border-color">
        <Link
          href={route('teacher.corrections.index')}
          className="text-xs font-comfortaa-bold text-teacher-color hover:underline flex items-center gap-1"
        >
          Voir toutes les actions <ChevronRight size={12} />
        </Link>
      </div>
    </div>
  );
}
