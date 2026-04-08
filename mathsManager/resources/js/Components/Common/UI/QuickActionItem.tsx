import { Link } from '@inertiajs/react';
import type { QuickAction } from '@/types';

interface Props {
  action: QuickAction;
  animationDelay: number;
  onNavigate: () => void;
}

export default function QuickActionItem({ action, animationDelay, onNavigate }: Props) {
  const Icon = action.icon;

  const iconEl = (
    <span
      className={`flex items-center justify-center w-7 h-7 rounded-lg shrink-0 transition-colors ${
        action.disabled
          ? 'bg-surface-color text-text-gray'
          : 'bg-teacher-color/15 text-teacher-color group-hover:bg-teacher-color/25'
      }`}
    >
      <Icon size={14} />
    </span>
  );

  const itemClass = `
    group flex items-center gap-2.5 px-3 py-2.5 rounded-xl
    border border-border-color bg-secondary-color/90 backdrop-blur-sm shadow-md
    animate-slideInRight transition-colors w-full text-left
    ${action.disabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-surface-color cursor-pointer'}
  `;

  const content = (
    <>
      {iconEl}
      <span className="flex-1 text-sm font-medium text-text-color">{action.label}</span>
      {action.comingSoon && (
        <span className="text-[10px] font-bold tracking-wider text-text-gray/60 bg-surface-color px-1.5 py-0.5 rounded-md uppercase shrink-0">
          Bientôt
        </span>
      )}
      {!action.comingSoon && !!action.badge && action.badge > 0 && (
        <span className="text-[10px] font-bold bg-error-color text-white w-5 h-5 flex items-center justify-center rounded-full shrink-0">
          {action.badge > 9 ? '9+' : action.badge}
        </span>
      )}
    </>
  );

  const style = { animationDelay: `${animationDelay}ms` };

  return action.href && !action.disabled ? (
    <Link href={action.href} className={itemClass} style={style} onClick={onNavigate}>
      {content}
    </Link>
  ) : (
    <button disabled={action.disabled} className={itemClass} style={style}>
      {content}
    </button>
  );
}
