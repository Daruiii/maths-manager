import { ReactNode } from 'react';
import { X } from 'lucide-react';
import IconButton from '@/Components/Common/UI/IconButton';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  title: string;
  subtitle?: string;
  footer?: ReactNode;
  children: ReactNode;
}

export default function SlidePanel({ isOpen, onClose, title, subtitle, footer, children }: Props) {
  if (!isOpen) return null;

  return (
    <>
      {/* Backdrop */}
      <div className="fixed inset-0 bg-black/40 z-40 backdrop-blur-sm" onClick={onClose} />

      {/* Panel */}
      <div className="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-primary-color border-l border-border-color flex flex-col shadow-2xl">
        {/* Header */}
        <div className="p-4 border-b border-border-color flex items-center justify-between flex-shrink-0">
          <div>
            <h3 className="font-comfortaa-bold text-text-color">{title}</h3>
            {subtitle && <p className="text-xs text-text-gray mt-0.5">{subtitle}</p>}
          </div>
          <IconButton icon={X} iconSize={18} onClick={onClose} />
        </div>

        {/* Body */}
        <div className="flex-1 overflow-y-auto p-4 space-y-4">{children}</div>

        {/* Footer */}
        {footer && <div className="p-4 border-t border-border-color flex-shrink-0">{footer}</div>}
      </div>
    </>
  );
}
