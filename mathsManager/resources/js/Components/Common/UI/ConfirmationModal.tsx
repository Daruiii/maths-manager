import { ReactNode } from 'react';
import Modal from '@/Components/Common/UI/Modal';
import Button from '@/Components/Common/UI/Button';
import { AlertTriangle, Info, CheckCircle, AlertCircle } from 'lucide-react';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  onConfirm: () => void;
  title: string;
  description?: ReactNode;
  confirmText?: string;
  cancelText?: string;
  type?: 'danger' | 'warning' | 'info' | 'success';
  children?: ReactNode;
  isSubmitting?: boolean;
}

export default function ConfirmationModal({
  isOpen,
  onClose,
  onConfirm,
  title,
  description,
  confirmText = 'Confirmer',
  cancelText = 'Annuler',
  type = 'info',
  children,
  isSubmitting = false,
}: Props) {
  const getConfig = () => {
    switch (type) {
      case 'danger':
        return {
          icon: AlertTriangle,
          iconClass: 'bg-error-color/10 text-error-color',
          buttonVariant: 'danger' as const,
        };
      case 'warning':
        return {
          icon: AlertCircle,
          iconClass: 'bg-warning-color/10 text-warning-color',
          buttonVariant: 'primary' as const,
        };
      case 'success':
        return {
          icon: CheckCircle,
          iconClass: 'bg-success-color/10 text-success-color',
          buttonVariant: 'primary' as const,
        };
      case 'info':
      default:
        return {
          icon: Info,
          iconClass: 'bg-tertiary-color/10 text-tertiary-color',
          buttonVariant: 'primary' as const,
        };
    }
  };

  const { icon: Icon, iconClass, buttonVariant } = getConfig();

  return (
    <Modal show={isOpen} onClose={onClose} maxWidth="md">
      <div className="p-6 sm:p-8">
        <div className="flex items-center gap-4 mb-6">
          <div className={`p-3 rounded-full flex-shrink-0 ${iconClass}`}>
            <Icon size={28} />
          </div>
          <div>
            <h3 className="text-xl sm:text-2xl font-black text-text-color">{title}</h3>
            {type === 'danger' && (
              <p className="text-error-color text-xs mt-1 font-bold tracking-wider uppercase">
                Action irréversible
              </p>
            )}
          </div>
        </div>

        {description && (
          <div className="bg-surface-color border border-border-color rounded-xl p-4 sm:p-5 mb-6">
            <div className="text-text-color leading-relaxed">{description}</div>
          </div>
        )}

        {children && <div className="mb-6">{children}</div>}

        <div className="flex flex-col-reverse sm:flex-row justify-center gap-3 pt-5 border-t border-border-color">
          <Button
            variant="secondary"
            onClick={onClose}
            className="w-full sm:w-auto"
            disabled={isSubmitting}
            size="sm"
          >
            {cancelText}
          </Button>
          <Button
            variant={buttonVariant}
            onClick={onConfirm}
            className="w-full sm:w-auto"
            isLoading={isSubmitting}
            size="sm"
          >
            {confirmText}
          </Button>
        </div>
      </div>
    </Modal>
  );
}
