import { ReactNode } from 'react';
import { Transition } from '@headlessui/react';
import { X } from 'lucide-react';
import IconButton from '@/Components/Common/UI/IconButton';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  title: string;
  subtitle?: string;
  footer?: ReactNode;
  children: ReactNode;
  /** Panel width. 'sm' = max-w-sm (384px) for lists, 'md' = max-w-md (448px) for richer content. Default: 'md' */
  size?: 'sm' | 'md';
}

const SIZE_CLASS: Record<NonNullable<Props['size']>, string> = {
  sm: 'max-w-sm',
  md: 'max-w-md',
};

/**
 * SlidePanel — panneau latéral animé (slide depuis la droite).
 *
 * Animation :
 *   - Backdrop : fade in/out (opacity 0 → 1)
 *   - Panel    : slide depuis la droite (translate-x-full → translate-x-0)
 *
 * Usage :
 *   <SlidePanel isOpen={open} onClose={() => setOpen(false)} title="Assigner" size="sm">
 *     …contenu…
 *   </SlidePanel>
 */
export default function SlidePanel({
  isOpen,
  onClose,
  title,
  subtitle,
  footer,
  children,
  size = 'md',
}: Props) {
  return (
    <>
      {/* ── Backdrop ── */}
      <Transition show={isOpen}>
        <div
          onClick={onClose}
          className={[
            'fixed inset-0 bg-black/40 backdrop-blur-sm z-40',
            'transition-opacity duration-300 ease-out',
            'data-[closed]:opacity-0',
          ].join(' ')}
        />
      </Transition>

      {/* ── Panel ── */}
      <Transition show={isOpen}>
        <div
          className={[
            'fixed inset-y-0 right-0 z-50 w-full',
            SIZE_CLASS[size],
            'bg-primary-color border-l border-border-color flex flex-col shadow-2xl',
            'transition-transform duration-300 ease-out',
            'data-[closed]:translate-x-full',
          ].join(' ')}
        >
          {/* Header */}
          <div className="p-4 border-b border-border-color flex items-center justify-between flex-shrink-0">
            <div>
              <h3 className="font-comfortaa-bold text-text-color">{title}</h3>
              {subtitle && <p className="text-xs text-text-gray mt-0.5">{subtitle}</p>}
            </div>
            <IconButton icon={X} iconSize={18} onClick={onClose} />
          </div>

          {/* Body */}
          <div className="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">{children}</div>

          {/* Footer */}
          {footer && <div className="p-4 border-t border-border-color flex-shrink-0">{footer}</div>}
        </div>
      </Transition>
    </>
  );
}
