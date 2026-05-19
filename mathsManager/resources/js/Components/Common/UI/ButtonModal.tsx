import { ReactNode, useEffect, useRef } from 'react';
import { Transition } from '@headlessui/react';

interface ButtonModalProps {
  isOpen: boolean;
  onOpenChange: (open: boolean) => void;
  trigger: ReactNode;
  children: ReactNode;
  panelClassName?: string;
  align?: 'left' | 'right';
  panelWidthClassName?: string;
}

export default function ButtonModal({
  isOpen,
  onOpenChange,
  trigger,
  children,
  panelClassName = '',
  align = 'right',
  panelWidthClassName = 'w-[min(16rem,calc(100vw-2rem))]',
}: ButtonModalProps) {
  const containerRef = useRef<HTMLDivElement | null>(null);

  useEffect(() => {
    if (!isOpen) return;

    const onPointerDown = (event: MouseEvent) => {
      if (!containerRef.current?.contains(event.target as Node)) {
        onOpenChange(false);
      }
    };

    const onEscape = (event: KeyboardEvent) => {
      if (event.key === 'Escape') {
        onOpenChange(false);
      }
    };

    document.addEventListener('mousedown', onPointerDown);
    document.addEventListener('keydown', onEscape);
    return () => {
      document.removeEventListener('mousedown', onPointerDown);
      document.removeEventListener('keydown', onEscape);
    };
  }, [isOpen, onOpenChange]);

  return (
    <div ref={containerRef} className="relative inline-flex">
      <button
        type="button"
        aria-expanded={isOpen}
        aria-haspopup="dialog"
        className="leading-none"
        onClick={() => onOpenChange(!isOpen)}
      >
        {trigger}
      </button>

      <Transition
        show={isOpen}
        enter="transition duration-150 ease-out"
        enterFrom="opacity-0 -translate-y-1 scale-95"
        enterTo="opacity-100 translate-y-0 scale-100"
        leave="transition duration-100 ease-in"
        leaveFrom="opacity-100 translate-y-0 scale-100"
        leaveTo="opacity-0 -translate-y-1 scale-95"
      >
        <div
          role="dialog"
          className={`absolute top-full mt-2 z-40 ${panelWidthClassName} rounded-xl border border-border-color bg-surface-color shadow-lg p-2.5 ${
            align === 'left' ? 'left-0' : 'right-0'
          } ${panelClassName}`}
        >
          {children}
        </div>
      </Transition>
    </div>
  );
}
