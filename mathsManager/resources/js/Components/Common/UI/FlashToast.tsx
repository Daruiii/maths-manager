import { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Transition } from '@headlessui/react';
import { CheckCircle, XCircle, AlertTriangle, Info, X } from 'lucide-react';

interface FlashToastProps {
  message?: string;
  type?: 'success' | 'error' | 'warning' | 'info';
  onClose?: () => void;
}

export default function FlashToast({
  message: propMessage,
  type: propType,
  onClose,
}: FlashToastProps = {}) {
  const { flash } = usePage<PageProps>().props;
  const [show, setShow] = useState(false);
  const [message, setMessage] = useState<{
    type: 'success' | 'error' | 'warning' | 'info';
    text: string;
  } | null>(null);

  // Consolidate logic: Props take precedence, then Flash messages
  useEffect(() => {
    // 1. Direct Props usage (Manual trigger)
    if (propMessage && propType) {
      setMessage({ type: propType, text: propMessage });
      setShow(true);
      return;
    }

    // 2. Inertia Flash Messages (Auto trigger on navigation)
    // We check if any flash message exists and is different from current to avoid loops
    // (Though here we just set on mount/update of flash)
    const flashType = (Object.keys(flash || {}) as Array<keyof typeof flash>).find(
      (key) => ['success', 'error', 'warning', 'info'].includes(key) && flash?.[key]
    );

    if (flashType) {
      setMessage({
        type: flashType as 'success' | 'error' | 'warning' | 'info',
        text: flash![flashType]!,
      });
      setShow(true);
    }
  }, [flash, propMessage, propType]);

  // Auto-hide timer
  useEffect(() => {
    if (show) {
      const timer = setTimeout(() => {
        setShow(false);
        if (onClose) onClose();
      }, 5000);
      return () => clearTimeout(timer);
    }
  }, [show, onClose]);

  if (!message) return null;

  const icons = {
    success: <CheckCircle className="w-6 h-6" />,
    error: <XCircle className="w-6 h-6" />,
    warning: <AlertTriangle className="w-6 h-6" />,
    info: <Info className="w-6 h-6" />,
  };

  const colors = {
    // Using semantic colors with opacity via Tailwind utilities defined in tailwind.config.js
    success: 'bg-success-color/10 border-success-color/20 text-success-color',
    error: 'bg-error-color/10 border-error-color/20 text-error-color',
    warning: 'bg-warning-color/10 border-warning-color/20 text-warning-color',
    info: 'bg-info-color/10 border-info-color/20 text-info-color',
  };

  return (
    <div className="fixed top-24 right-4 z-[9999] flex flex-col gap-2 w-full max-w-sm">
      <Transition
        show={show}
        enter="transform ease-out duration-300 transition"
        enterFrom="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enterTo="translate-y-0 opacity-100 sm:translate-x-0"
        leave="transition ease-in duration-100"
        leaveFrom="opacity-100"
        leaveTo="opacity-0"
      >
        <div className={`rounded-xl border shadow-lg p-4 ${colors[message.type]} relative`}>
          <div className="flex items-start gap-3">
            <div className="flex-shrink-0">{icons[message.type]}</div>
            <div className="flex-1 pt-0.5">
              <p className="text-sm font-comfortaa-bold">
                {message.type === 'success' && 'Succès'}
                {message.type === 'error' && 'Erreur'}
                {message.type === 'warning' && 'Attention'}
                {message.type === 'info' && 'Information'}
              </p>
              <p className="mt-1 text-sm opacity-90">{message.text}</p>
            </div>
            <div className="flex-shrink-0 ml-4 flex">
              <button
                className="bg-transparent rounded-md inline-flex hover:opacity-75 focus:outline-none transition-opacity"
                onClick={() => {
                  setShow(false);
                  if (onClose) onClose();
                }}
              >
                <span className="sr-only">Fermer</span>
                <X className="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </div>
  );
}
