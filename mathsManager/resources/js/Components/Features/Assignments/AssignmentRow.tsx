import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import { BATCH_STATUS_META } from '@/Constants/statuses';

export interface AssignmentBrief {
  id: number;
  custom_title: string | null;
  custom_level: string | null;
  teacher: { first_name: string; last_name: string } | null;
  status: string;
}

export default function AssignmentRow({
  href,
  title,
  teacher,
  level,
  status,
  grade,
}: {
  href: string;
  title: string;
  teacher: { first_name: string; last_name: string } | null;
  level: string | null;
  status: string;
  grade?: number | null;
}) {
  const meta = BATCH_STATUS_META[status] ?? BATCH_STATUS_META.not_started;

  return (
    <Link
      href={href}
      className="flex items-center gap-3 px-4 py-3 rounded-2xl bg-secondary-color border border-border-color hover:border-student-color/40 transition-colors group"
    >
      <div className="flex-1 min-w-0">
        <p className="font-comfortaa-bold text-text-color truncate">{title}</p>
        {teacher && (
          <p className="text-xs text-text-gray mt-0.5">
            {teacher.first_name} {teacher.last_name}
            {level && <span className="ml-2 text-student-color">{level}</span>}
          </p>
        )}
      </div>
      {grade != null && (
        <span className="text-sm font-comfortaa-bold text-success-color shrink-0">{grade}/20</span>
      )}
      <span
        className={`text-xs px-2 py-0.5 rounded-full font-comfortaa-bold shrink-0 ${meta.classes}`}
      >
        {meta.label}
      </span>
      <ChevronRight
        size={16}
        className="text-text-gray shrink-0 group-hover:text-student-color transition-colors"
      />
    </Link>
  );
}
