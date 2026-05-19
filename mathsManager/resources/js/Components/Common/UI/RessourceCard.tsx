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
  { accent: string; iconBg: string; iconText: string; count: string }
> = {
  teacher: {
    accent: 'border-l-teacher-color hover:bg-teacher-color/[0.03]',
    iconBg: 'bg-teacher-color/10',
    iconText: 'text-teacher-color',
    count: 'text-teacher-color',
  },
  student: {
    accent: 'border-l-student-color hover:bg-student-color/[0.03]',
    iconBg: 'bg-student-color/10',
    iconText: 'text-student-color',
    count: 'text-student-color',
  },
  admin: {
    accent: 'border-l-admin-color hover:bg-admin-color/[0.03]',
    iconBg: 'bg-admin-color/10',
    iconText: 'text-admin-color',
    count: 'text-admin-color',
  },
  tertiary: {
    accent: 'border-l-tertiary-color hover:bg-tertiary-color/[0.03]',
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
      className={`h-full relative p-3 sm:p-4 bg-secondary-color border border-border-color border-l-2 ${styles.accent} rounded-2xl transition-all duration-200 ${
        available
          ? 'hover:-translate-y-0.5 hover:shadow-warm-sm cursor-pointer'
          : 'opacity-60 cursor-default'
      }`}
    >
      <div className="flex items-start justify-between mb-2 sm:mb-3">
        <div className={`p-1.5 sm:p-2 ${styles.iconBg} rounded-xl`}>
          <Icon size={16} className={`${styles.iconText} sm:w-[18px] sm:h-[18px]`} />
        </div>
        {available ? (
          <ChevronRight
            size={15}
            className="text-text-gray/50 mt-1 group-hover:text-text-gray transition-colors"
          />
        ) : (
          <span className="mm-badge mm-badge-neutral text-[10px]">Bientôt</span>
        )}
      </div>

      <p className="text-sm font-comfortaa-bold text-text-color leading-tight">{title}</p>
      <p className="text-[11px] sm:text-xs text-text-gray mt-0.5 leading-tight">{subtitle}</p>

      {available && count !== undefined && (
        <p className={`text-xs font-comfortaa-bold mt-1.5 sm:mt-2 tabular-nums ${styles.count}`}>
          {count} {count !== 1 ? 'éléments' : 'élément'}
        </p>
      )}
    </div>
  );

  return available ? (
    <Link href={href} className="block w-full group">
      {inner}
    </Link>
  ) : (
    inner
  );
}
