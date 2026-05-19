import { ChevronLeft, ChevronRight } from 'lucide-react';

interface Props {
  page: number;
  totalPages: number;
  onPageChange: (page: number) => void;
  /** Texte informatif optionnel affiché entre les boutons */
  info?: string;
  accentColor?: 'teacher' | 'admin' | 'student';
}

const hoverBorderByAccent: Record<string, string> = {
  teacher: 'hover:border-teacher-color/50',
  admin: 'hover:border-admin-color/50',
  student: 'hover:border-student-color/50',
};

export default function Pagination({
  page,
  totalPages,
  onPageChange,
  info,
  accentColor = 'teacher',
}: Props) {
  if (totalPages <= 1) return null;

  const hoverBorder = hoverBorderByAccent[accentColor];
  const btnBase = `p-2 rounded-xl border border-border-color text-text-gray hover:text-text-color ${hoverBorder} disabled:opacity-40 disabled:cursor-not-allowed transition-colors`;

  return (
    <div className="flex items-center justify-center gap-3 pt-2">
      <button
        onClick={() => onPageChange(Math.max(1, page - 1))}
        disabled={page === 1}
        className={btnBase}
      >
        <ChevronLeft size={16} />
      </button>

      <span className="text-sm text-text-gray">
        Page <span className="font-bold text-text-color">{page}</span> / {totalPages}
        {info && <span className="ml-2 text-xs opacity-60">({info})</span>}
      </span>

      <button
        onClick={() => onPageChange(Math.min(totalPages, page + 1))}
        disabled={page === totalPages}
        className={btnBase}
      >
        <ChevronRight size={16} />
      </button>
    </div>
  );
}
