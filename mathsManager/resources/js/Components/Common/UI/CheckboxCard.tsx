import { ReactNode } from 'react';
import { Check } from 'lucide-react';

interface Props {
  isSelected: boolean;
  onToggle?: () => void;
  children: ReactNode;
  /** 'button' = entire card is clickable. 'div' = card is a container (inner buttons need to work). Default: 'button' */
  as?: 'button' | 'div';
  className?: string;
}

export default function CheckboxCard({
  isSelected,
  onToggle,
  children,
  as: Tag = 'button',
  className = '',
}: Props) {
  const baseClass = `w-full text-left rounded-xl border-2 transition-colors ${
    isSelected
      ? 'border-teacher-color bg-teacher-color/5'
      : 'border-border-color bg-secondary-color hover:border-teacher-color/50'
  } ${className}`;

  if (Tag === 'div') {
    return <div className={baseClass}>{children}</div>;
  }

  return (
    <button type="button" onClick={onToggle ?? undefined} className={baseClass}>
      {children}
    </button>
  );
}

/** Standalone checkbox indicator — use inside CheckboxCard */
export function CheckboxIndicator({
  isSelected,
  onToggle,
}: {
  isSelected: boolean;
  onToggle?: () => void;
}) {
  if (onToggle) {
    return (
      <button
        type="button"
        onClick={(e: React.MouseEvent) => {
          e.stopPropagation();
          onToggle();
        }}
        className={`w-5 h-5 rounded-md border-2 flex items-center justify-center flex-shrink-0 transition-colors ${
          isSelected
            ? 'bg-teacher-color border-teacher-color text-white'
            : 'border-border-color hover:border-teacher-color'
        }`}
      >
        {isSelected && <Check size={12} />}
      </button>
    );
  }

  return (
    <span
      className={`w-5 h-5 rounded-md border-2 flex items-center justify-center flex-shrink-0 transition-colors ${
        isSelected ? 'bg-teacher-color border-teacher-color text-white' : 'border-border-color'
      }`}
    >
      {isSelected && <Check size={12} />}
    </span>
  );
}
