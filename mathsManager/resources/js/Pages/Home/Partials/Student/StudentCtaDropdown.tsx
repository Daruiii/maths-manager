import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import type { FlatItem } from '@/Pages/Home/Partials/AssignmentItem';

const TYPE_COLOR: Record<string, string> = {
  ds: 'text-tertiary-color',
  dm: 'text-admin-color',
  td: 'text-info-color',
};

interface Props {
  items: FlatItem[];
  position: { top: number; left: number };
  onMouseEnter: () => void;
  onMouseLeave: () => void;
}

export default function StudentCtaDropdown({ items, position, onMouseEnter, onMouseLeave }: Props) {
  return (
    <div
      style={{
        position: 'fixed',
        top: position.top,
        left: position.left,
        zIndex: 9999,
        width: 256,
      }}
      className="bg-secondary-color border border-border-color rounded-xl shadow-warm-sm py-1 animate-fadeInUp"
      onMouseEnter={onMouseEnter}
      onMouseLeave={onMouseLeave}
    >
      {items.map((item) => (
        <Link
          key={item.href}
          href={item.href}
          className="flex items-center gap-2 px-3 py-2.5 hover:bg-surface-color transition-colors"
        >
          <span
            className={`text-[10px] font-comfortaa-bold uppercase tracking-widest shrink-0 ${TYPE_COLOR[item.type]}`}
          >
            {item.type.toUpperCase()}
          </span>
          <span className="flex-1 text-sm font-comfortaa-bold text-text-color truncate">
            {item.title}
          </span>
          <ChevronRight size={12} className="text-text-gray shrink-0" />
        </Link>
      ))}
      <div className="border-t border-border-color mt-1 pt-1">
        <Link
          href={route('student.assignments.index')}
          className="flex items-center gap-2 px-3 py-2.5 hover:bg-surface-color transition-colors"
        >
          <span className="flex-1 text-xs font-comfortaa-bold text-text-gray">
            Voir tous mes travaux
          </span>
          <ChevronRight size={12} className="text-text-gray shrink-0" />
        </Link>
      </div>
    </div>
  );
}
