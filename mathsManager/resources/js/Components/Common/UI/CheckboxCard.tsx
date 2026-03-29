import { ReactNode } from 'react';
import { Check, Minus } from 'lucide-react';

interface Props {
  isSelected: boolean;
  onToggle?: () => void;
  children: ReactNode;
  /** 'button' = entire card is clickable. 'div' = card is a container (inner buttons need to work). Default: 'button' */
  as?: 'button' | 'div';
  className?: string;
}

/**
 * CheckboxCard — carte sélectionnable style MathsManager.
 *
 * État sélectionné : bordure `border-teacher-color/60` + fond `bg-teacher-color/5`
 * (subtle, token-only — cf. Styleguide > Composants)
 *
 * Usage :
 *   <CheckboxCard isSelected={selected} onToggle={() => toggle(id)}>
 *     <CheckboxIndicator isSelected={selected} />
 *     Contenu
 *   </CheckboxCard>
 */
export default function CheckboxCard({
  isSelected,
  onToggle,
  children,
  as: Tag = 'button',
  className = '',
}: Props) {
  const baseClass = [
    'w-full text-left rounded-xl border transition-colors',
    isSelected
      ? 'border-teacher-color/60 bg-teacher-color/5'
      : 'border-border-color bg-secondary-color hover:border-teacher-color/40',
    className,
  ]
    .filter(Boolean)
    .join(' ');

  if (Tag === 'div') {
    return <div className={baseClass}>{children}</div>;
  }

  return (
    <button type="button" onClick={onToggle ?? undefined} className={baseClass}>
      {children}
    </button>
  );
}

/**
 * Standalone checkbox indicator — use inside CheckboxCard.
 *
 * `indeterminate` : état partiel (sélection de groupe incomplète) — affiche un tiret.
 */
export function CheckboxIndicator({
  isSelected,
  indeterminate = false,
  onToggle,
}: {
  isSelected: boolean;
  indeterminate?: boolean;
  onToggle?: () => void;
}) {
  const active = isSelected || indeterminate;

  const colorClass = active
    ? indeterminate
      ? 'bg-teacher-color/40 border-teacher-color/60 text-white'
      : 'bg-teacher-color border-teacher-color text-white'
    : 'border-border-color hover:border-teacher-color';

  const icon = indeterminate ? <Minus size={12} /> : isSelected ? <Check size={12} /> : null;

  const baseClass = `w-5 h-5 rounded-md border-2 flex items-center justify-center flex-shrink-0 transition-colors ${colorClass}`;

  if (onToggle) {
    return (
      <button
        type="button"
        onClick={(e: React.MouseEvent) => {
          e.stopPropagation();
          onToggle();
        }}
        className={baseClass}
      >
        {icon}
      </button>
    );
  }

  return <span className={baseClass}>{icon}</span>;
}
