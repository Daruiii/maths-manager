import { Link } from '@inertiajs/react';
import { CheckCircle, ChevronRight, Clock } from 'lucide-react';
import StudentTypeBadge from '@/Pages/Home/Partials/Student/StudentTypeBadge';
import type { HomeFeedbackItem, HomeFeedbackSummary } from '@/types';

interface Props {
  summary?: HomeFeedbackSummary;
  items?: HomeFeedbackItem[];
}

export default function StudentFeedbackList({ summary, items = [] }: Props) {
  if (items.length === 0) return null;

  const correctedCount = summary?.corrected ?? 0;
  const pendingCount = summary?.pending ?? 0;

  return (
    <div className="border-t border-border-color px-4 py-3 space-y-2.5">
      <div>
        <p className="text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray">
          Corrections & copies
        </p>
        <p className="mt-0.5 text-[10px] text-text-gray">
          {correctedCount} corrigée{correctedCount > 1 ? 's' : ''} · {pendingCount} en attente
        </p>
      </div>
      <div className="max-h-36 space-y-2 overflow-y-auto pr-1 custom-scrollbar">
        {items.map((item) => (
          <Link key={item.id} href={item.href} className="flex items-center gap-1.5 group">
            <StudentTypeBadge type={item.type} />
            <span className="flex-1 text-xs font-comfortaa-bold text-text-color truncate group-hover:text-student-color transition-colors">
              {item.title}
            </span>
            <FeedbackStatusBadge status={item.status} grade={item.grade} />
            <ChevronRight
              size={11}
              className="text-text-gray/50 shrink-0 group-hover:text-student-color transition-colors"
            />
          </Link>
        ))}
      </div>
    </div>
  );
}

function FeedbackStatusBadge({
  status,
  grade,
}: {
  status: 'pending' | 'corrected';
  grade: number | null;
}) {
  if (status === 'corrected') {
    return (
      <span className="inline-flex items-center gap-1 rounded-full bg-success-color/10 px-1.5 py-0.5 text-[9px] font-comfortaa-bold text-success-color shrink-0">
        <CheckCircle size={10} />
        {grade != null ? `${grade}/20` : 'Corrigé'}
      </span>
    );
  }

  return (
    <span className="inline-flex items-center gap-1 rounded-full bg-text-gray/10 px-1.5 py-0.5 text-[9px] font-comfortaa-bold text-text-gray shrink-0">
      <Clock size={10} />
      Attente
    </span>
  );
}
