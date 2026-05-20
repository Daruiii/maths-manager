import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import type { HomePendingCorrectionItem, HomeUnlockRequestItem } from '@/types';

export function timeAgo(dateStr: string): string {
  const diff = Date.now() - new Date(dateStr).getTime();
  const mins = Math.floor(diff / 60000);
  if (mins < 1) return 'maintenant';
  if (mins < 60) return `il y a ${mins} min`;
  const hrs = Math.floor(mins / 60);
  if (hrs < 24) return `il y a ${hrs}h`;
  return `il y a ${Math.floor(hrs / 24)}j`;
}

export interface BatchGroup {
  key: string;
  title: string;
  type: 'ds' | 'dm';
  href: string;
  items: HomePendingCorrectionItem[];
}

export function BatchCorrectionItem({ batch, index }: { batch: BatchGroup; index: number }) {
  const oldest = batch.items.reduce((a, b) =>
    new Date(a.created_at) < new Date(b.created_at) ? a : b
  );
  const n = batch.items.length;
  return (
    <Link
      href={batch.href}
      style={{ animationDelay: `${index * 40}ms` }}
      className="flex items-center gap-3 px-3 py-3 hover:bg-surface-color rounded-xl transition-colors group animate-fadeInUp"
    >
      <TypeBadge type={batch.type} size="md" />
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color truncate">{batch.title}</p>
        <p className="text-xs text-text-gray">
          <span className="font-comfortaa-bold text-text-color">{n}</span> copie{n > 1 ? 's' : ''} à
          corriger
        </p>
      </div>
      <span className="text-[10px] text-text-gray/60 shrink-0">{timeAgo(oldest.created_at)}</span>
      <ChevronRight
        size={14}
        className="text-text-gray group-hover:text-text-color transition-colors shrink-0"
      />
    </Link>
  );
}

export function BatchUnlockItem({ item, index }: { item: HomeUnlockRequestItem; index: number }) {
  const n = item.count;
  return (
    <Link
      href={item.batch_url ?? route('teacher.bureau.devoirs')}
      style={{ animationDelay: `${index * 40}ms` }}
      className="flex items-center gap-3 px-3 py-3 hover:bg-surface-color rounded-xl transition-colors group animate-fadeInUp"
    >
      <TypeBadge type="td" size="md" />
      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color truncate">{item.title}</p>
        <p className="text-xs text-text-gray">
          <span className="font-comfortaa-bold text-text-color">{n}</span> déblocage
          {n > 1 ? 's' : ''} demandé{n > 1 ? 's' : ''}
        </p>
      </div>
      <span className="text-[10px] text-text-gray/60 shrink-0">{timeAgo(item.updated_at)}</span>
      <ChevronRight
        size={14}
        className="text-text-gray group-hover:text-text-color transition-colors shrink-0"
      />
    </Link>
  );
}
