import { useEffect, useRef, useState } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Transition } from '@headlessui/react';
import { CheckCircle, XCircle, AlertTriangle, Info, X } from 'lucide-react';

interface FlashToastProps {
  message?: string;
  type?: 'success' | 'error' | 'warning' | 'info';
  onClose?: () => void;
  /** Ignore Inertia flash — pour les toasts prop-driven dans les pages qui ont déjà AppLayout */
  noFlash?: boolean;
}

export default function FlashToast({
  message: propMessage,
  type: propType,
  onClose,
  noFlash = false,
}: FlashToastProps = {}) {
  const { flash } = usePage<PageProps>().props;
  const [show, setShow] = useState(false);
  const [toastKey, setToastKey] = useState(0);
  const [message, setMessage] = useState<{
    type: 'success' | 'error' | 'warning' | 'info';
    text: string;
  } | null>(null);
  const showRef = useRef(false);
  showRef.current = show;

  function trigger(type: 'success' | 'error' | 'warning' | 'info', text: string) {
    if (showRef.current) {
      setShow(false);
      setTimeout(() => {
        setMessage({ type, text });
        setShow(true);
        setToastKey((k) => k + 1);
      }, 200);
    } else {
      setMessage({ type, text });
      setShow(true);
      setToastKey((k) => k + 1);
    }
  }

  useEffect(() => {
    if (propMessage && propType) trigger(propType, propMessage);
  }, [propMessage, propType]);

  useEffect(() => {
    if (noFlash) return;
    const flashType = (Object.keys(flash || {}) as Array<keyof typeof flash>).find(
      (key) => ['success', 'error', 'warning', 'info'].includes(key) && flash?.[key]
    );
    if (flashType) {
      trigger(flashType as 'success' | 'error' | 'warning' | 'info', flash![flashType]!);
    }
  }, [flash, noFlash]);

  useEffect(() => {
    if (!show) return;
    const timer = setTimeout(() => {
      setShow(false);
      onClose?.();
    }, 2000);
    return () => clearTimeout(timer);
  }, [toastKey]);

  if (!message) return null;

  const icons = {
    success: <CheckCircle className="w-5 h-5" />,
    error: <XCircle className="w-5 h-5" />,
    warning: <AlertTriangle className="w-5 h-5" />,
    info: <Info className="w-5 h-5" />,
  };

  const iconColors = {
    success: 'text-success-color',
    error: 'text-error-color',
    warning: 'text-warning-color',
    info: 'text-info-color',
  };

  return (
    <div className="fixed top-20 left-1/2 -translate-x-1/2 z-[9999] w-full max-w-sm px-4">
      <Transition
        show={show}
        enter="transform transition ease-out duration-300"
        enterFrom="-translate-y-12 opacity-0 scale-95"
        enterTo="translate-y-0 opacity-100 scale-100"
        leave="transition ease-in duration-200"
        leaveFrom="opacity-100 scale-100"
        leaveTo="opacity-0 scale-95"
      >
        <div className="rounded-xl border border-border-color bg-surface-color shadow-xl p-3.5">
          <div className="flex items-center gap-3">
            <div className={`flex-shrink-0 ${iconColors[message.type]}`}>{icons[message.type]}</div>
            <p className="flex-1 text-sm text-text-color">{message.text}</p>
            <button
              className="flex-shrink-0 text-text-gray hover:text-text-color transition-colors"
              onClick={() => {
                setShow(false);
                if (onClose) onClose();
              }}
            >
              <span className="sr-only">Fermer</span>
              <X className="w-4 h-4" />
            </button>
          </div>
        </div>
      </Transition>
    </div>
  );
}
