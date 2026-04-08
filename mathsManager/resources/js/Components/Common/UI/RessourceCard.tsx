import { LucideIcon, ChevronRight } from 'lucide-react';
import { Link } from '@inertiajs/react';

// ─── Types ────────────────────────────────────────────────────────────────────

type CardColor = 'teacher' | 'student' | 'admin' | 'tertiary';

interface Props {
  icon: LucideIcon;
  title: string;
  subtitle: string;
  count?: number;
  href: string;
  available?: boolean;
  color?: CardColor;
}

// ─── Style map ────────────────────────────────────────────────────────────────

const colorMap: Record<
  CardColor,
  { border: string; iconBg: string; iconText: string; count: string }
> = {
  teacher: {
    border: 'border-l-teacher-color',
    iconBg: 'bg-teacher-color/10',
    iconText: 'text-teacher-color',
    count: 'text-teacher-color',
  },
  student: {
    border: 'border-l-student-color',
    iconBg: 'bg-student-color/10',
    iconText: 'text-student-color',
    count: 'text-student-color',
  },
  admin: {
    border: 'border-l-admin-color',
    iconBg: 'bg-admin-color/10',
    iconText: 'text-admin-color',
    count: 'text-admin-color',
  },
  tertiary: {
    border: 'border-l-tertiary-color',
    iconBg: 'bg-tertiary-color/10',
    iconText: 'text-tertiary-color',
    count: 'text-tertiary-color',
  },
};

// ─── Composant ────────────────────────────────────────────────────────────────

export default function RessourceCard({
  icon: Icon,
  title,
  subtitle,
  count,
  href,
  available = true,
  color = 'tertiary',
}: Props) {
  const styles = colorMap[color];

  const inner = (
    <div
      className={`relative p-3 sm:p-4 bg-surface-color border border-border-color border-l-2 ${styles.border} rounded-2xl transition-transform duration-200 ${
        available ? 'hover:-translate-y-0.5' : 'opacity-60'
      }`}
    >
      <div className="flex items-start justify-between mb-2 sm:mb-3">
        <div className={`p-1.5 sm:p-2 ${styles.iconBg} rounded-xl`}>
          <Icon size={16} className={`${styles.iconText} sm:w-[18px] sm:h-[18px]`} />
        </div>
        {available ? (
          <ChevronRight size={16} className="text-text-gray mt-1" />
        ) : (
          <span className="text-xxs text-text-gray bg-border-color/40 px-2 py-0.5 rounded-full">
            Bientôt
          </span>
        )}
      </div>

      <p className="text-sm font-comfortaa-bold text-text-color leading-tight">{title}</p>
      <p className="text-[11px] sm:text-xs text-text-gray mt-0.5 leading-tight">{subtitle}</p>

      {available && count !== undefined && (
        <p className={`text-xs font-comfortaa-bold mt-1.5 sm:mt-2 ${styles.count}`}>
          {count} {count !== 1 ? 'éléments' : 'élément'}
        </p>
      )}
    </div>
  );

  return available ? (
    <Link href={href} className="block w-full">
      {inner}
    </Link>
  ) : (
    inner
  );
}
