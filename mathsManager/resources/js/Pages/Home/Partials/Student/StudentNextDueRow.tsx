import { Link } from '@inertiajs/react';
import { CalendarClock, ChevronRight } from 'lucide-react';
import StudentTypeBadge from '@/Pages/Home/Partials/Student/StudentTypeBadge';
import type { NextDue } from '@/Pages/Home/Partials/Student/studentSidebarTypes';

export default function StudentNextDueRow({ nextDue }: { nextDue: NextDue }) {
  return (
    <div className="border-t border-border-color px-4 py-3">
      {nextDue.urgent ? (
        <Link href={nextDue.href} className="flex items-center gap-1.5 group">
          <StudentTypeBadge type={nextDue.type} />
          <span className="flex-1 text-xs font-comfortaa-bold text-error-color truncate group-hover:opacity-75 transition-opacity">
            {nextDue.title}
          </span>
          <span className="text-[10px] font-comfortaa-bold text-error-color shrink-0">
            En retard
          </span>
          <ChevronRight size={11} className="text-error-color/50 shrink-0" />
        </Link>
      ) : (
        <Link href={nextDue.href} className="flex items-center gap-1.5 group">
          <CalendarClock size={11} className="text-text-gray shrink-0" />
          <StudentTypeBadge type={nextDue.type} />
          <span className="flex-1 text-xs font-comfortaa-bold text-text-color truncate group-hover:text-student-color transition-colors">
            {nextDue.title}
          </span>
          <span className="text-[10px] text-text-gray shrink-0">{nextDue.label}</span>
          <ChevronRight
            size={11}
            className="text-text-gray/50 shrink-0 group-hover:text-student-color transition-colors"
          />
        </Link>
      )}
    </div>
  );
}
