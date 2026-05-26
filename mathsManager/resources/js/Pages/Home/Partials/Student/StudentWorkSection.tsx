import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import type { FlatItem } from '@/Pages/Home/Partials/AssignmentItem';
import UrgentAssignmentCard from '@/Pages/Home/Partials/UrgentAssignmentCard';
import StudentAiTeaser from '@/Pages/Home/Partials/Student/StudentAiTeaser';

interface Props {
  total: number;
  displayItems: FlatItem[];
  remainingCount: number;
}

export default function StudentWorkSection({ total, displayItems, remainingCount }: Props) {
  if (total === 0) {
    return <StudentEmptyState />;
  }

  return (
    <>
      <div className="grid grid-cols-2 gap-3 items-stretch">
        {displayItems.map((item, i) => (
          <UrgentAssignmentCard key={`${item.type}-${item.id}`} item={item} index={i} tile />
        ))}
      </div>
      <Link
        href={route('student.assignments.index')}
        className="flex items-center justify-center gap-1.5 text-xs font-comfortaa-bold text-text-gray hover:text-student-color transition-colors py-1"
      >
        Voir tous mes devoirs
        {remainingCount > 0 && (
          <span className="font-cmu-serif text-text-gray/70">+{remainingCount}</span>
        )}
        <ChevronRight size={11} />
      </Link>
      <StudentAiTeaser />
    </>
  );
}

function StudentEmptyState() {
  return (
    <div className="relative mm-card mm-card-style-halo mm-card-accent-student rounded-2xl py-12 flex flex-col items-center gap-4 text-center overflow-hidden animate-fadeInUp">
      <div
        className="absolute inset-0 flex items-center justify-center pointer-events-none"
        aria-hidden
      >
        <span className="text-[100px] font-cmu-serif text-student-color opacity-[0.04] leading-none">
          ∞
        </span>
      </div>
      <div className="relative space-y-1">
        <p className="text-sm font-comfortaa-bold text-text-color">Aucun devoir en cours</p>
        <p className="text-xs text-text-gray">
          Tu es à jour — profite pour explorer les ressources.
        </p>
      </div>
      <Link
        href={route('student.ressources')}
        className="relative inline-flex items-center gap-1.5 text-xs font-comfortaa-bold text-student-color border border-student-color/30 bg-student-color/5 hover:bg-student-color/10 rounded-xl px-3 py-1.5 transition-colors"
      >
        Explorer les ressources <ChevronRight size={12} />
      </Link>
    </div>
  );
}
