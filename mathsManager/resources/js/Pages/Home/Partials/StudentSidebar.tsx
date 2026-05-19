import { Link } from '@inertiajs/react';
import { ChevronRight, CalendarClock } from 'lucide-react';

const TYPE_LABEL: Record<string, string> = { ds: 'DS', dm: 'DM', td: 'TD' };
const TYPE_COLOR: Record<string, string> = {
  ds: 'text-tertiary-color',
  dm: 'text-admin-color',
  td: 'text-info-color',
};
const TYPE_BG: Record<string, string> = {
  ds: 'bg-tertiary-color/10',
  dm: 'bg-admin-color/10',
  td: 'bg-info-color/10',
};

interface NextDue {
  label: string;
  title: string;
  type: string;
  href: string;
  urgent: boolean;
}

interface Props {
  averageGrade?: number | null;
  nextDue?: NextDue | null;
}

export default function StudentSidebar({ averageGrade, nextDue }: Props) {
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

        {nextDue && (
          <div className="space-y-1 pt-1">
            {nextDue.urgent ? (
              <>
                <p className="text-[10px] font-comfortaa-bold text-error-color uppercase tracking-widest">
                  Devoir en retard
                </p>
                <Link href={nextDue.href} className="flex items-center gap-1.5 group">
                  <span
                    className={`text-[9px] font-comfortaa-bold uppercase px-1.5 py-0.5 rounded shrink-0 ${TYPE_COLOR[nextDue.type] ?? 'text-text-gray'} ${TYPE_BG[nextDue.type] ?? ''}`}
                  >
                    {TYPE_LABEL[nextDue.type] ?? nextDue.type.toUpperCase()}
                  </span>
                  <span className="flex-1 text-xs font-comfortaa-bold text-error-color truncate group-hover:opacity-75 transition-opacity">
                    {nextDue.title}
                  </span>
                  <ChevronRight
                    size={11}
                    className="text-error-color/50 shrink-0 group-hover:text-error-color transition-colors"
                  />
                </Link>
              </>
            ) : (
              <>
                <div className="flex items-center gap-1.5">
                  <CalendarClock size={11} className="text-text-gray shrink-0" />
                  <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest">
                    Prochaine échéance
                  </p>
                </div>
                <Link href={nextDue.href} className="flex items-center gap-1.5 pl-4 group">
                  <span
                    className={`text-[9px] font-comfortaa-bold uppercase px-1.5 py-0.5 rounded shrink-0 ${TYPE_COLOR[nextDue.type] ?? 'text-text-gray'} ${TYPE_BG[nextDue.type] ?? ''}`}
                  >
                    {TYPE_LABEL[nextDue.type] ?? nextDue.type.toUpperCase()}
                  </span>
                  <span className="flex-1 text-xs font-comfortaa-bold text-text-color truncate group-hover:text-student-color transition-colors">
                    {nextDue.title}
                  </span>
                  <ChevronRight
                    size={11}
                    className="text-text-gray shrink-0 group-hover:text-student-color transition-colors"
                  />
                </Link>
                <p className="text-[10px] text-text-gray pl-4">{nextDue.label}</p>
              </>
            )}
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
