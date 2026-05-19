import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';

interface Props {
  averageGrade?: number | null;
  correctedCount: number;
}

export default function StudentSidebar({ averageGrade, correctedCount }: Props) {
  return (
    <aside className="space-y-4 lg:sticky lg:top-6">
      <div className="bg-secondary-color border border-border-color rounded-2xl p-4 space-y-4">
        <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest">
          Mes stats
        </p>
        {averageGrade != null ? (
          <div>
            <div className="flex items-baseline gap-0.5">
              <span className="text-4xl font-cmu-serif text-text-color leading-none">
                {averageGrade.toFixed(1)}
              </span>
              <span className="text-xl font-cmu-serif text-text-gray">/20</span>
            </div>
            <p className="mm-stat-label">moyenne générale</p>
          </div>
        ) : (
          <p className="text-sm text-text-gray italic">Pas encore de note</p>
        )}
        {correctedCount > 0 && (
          <div>
            <span className="text-2xl font-cmu-serif text-text-color leading-none">
              {correctedCount}
            </span>
            <p className="mm-stat-label">
              copie{correctedCount > 1 ? 's' : ''} corrigée{correctedCount > 1 ? 's' : ''}
            </p>
          </div>
        )}
        <Link
          href={route('student.ressources')}
          className="flex items-center gap-1 text-xs font-comfortaa-bold text-student-color hover:underline"
        >
          Mes ressources <ChevronRight size={12} />
        </Link>
      </div>
    </aside>
  );
}
