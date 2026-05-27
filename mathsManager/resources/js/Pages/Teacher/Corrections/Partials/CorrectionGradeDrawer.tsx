import { X } from 'lucide-react';
import { studentName } from '@/Components/Features/Corrections/correctionRequestLabels';
import CorrectionSentPanel from '@/Pages/Teacher/Corrections/Partials/CorrectionSentPanel';
import EditCorrectionForm from '@/Pages/Teacher/Corrections/Partials/EditCorrectionForm';
import SendCorrectionForm from '@/Pages/Teacher/Corrections/Partials/SendCorrectionForm';
import type { CorrectionRequest } from '@/types/models';

interface Props {
  correctionRequest: CorrectionRequest;
  isOpen: boolean;
  onClose: () => void;
  isEditing: boolean;
  onEdit: () => void;
  onSaved: () => void;
  onCancelEdit: () => void;
}

export default function CorrectionGradeDrawer({
  correctionRequest,
  isOpen,
  onClose,
  isEditing,
  onEdit,
  onSaved,
  onCancelEdit,
}: Props) {
  const isCorrected = correctionRequest.status === 'corrected';
  const name = studentName(correctionRequest);

  const drawerLabel = isEditing
    ? 'Modifier la correction'
    : isCorrected
      ? 'Correction envoyée'
      : 'Noter cette copie';

  return (
    <>
      {/* Backdrop */}
      <div
        className={`fixed inset-0 z-40 bg-text-color/20 backdrop-blur-sm transition-opacity duration-300 ${
          isOpen ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'
        }`}
        onClick={onClose}
        aria-hidden="true"
      />

      {/* Drawer panel */}
      <div
        role="dialog"
        aria-modal="true"
        aria-label="Formulaire de correction"
        className={`fixed inset-y-0 right-0 z-50 flex w-full max-w-md flex-col bg-primary-color shadow-warm-sm transition-transform duration-300 ease-out ${
          isOpen ? 'translate-x-0' : 'translate-x-full'
        }`}
      >
        {/* Header */}
        <div className="flex shrink-0 items-center justify-between gap-3 border-b border-border-color px-5 py-4">
          <div>
            <p className="text-[11px] font-comfortaa-bold uppercase tracking-widest text-teacher-color">
              {drawerLabel}
            </p>
            <p className="mt-0.5 font-comfortaa-bold text-text-color">{name}</p>
          </div>
          <button
            type="button"
            onClick={onClose}
            className="flex h-8 w-8 items-center justify-center rounded-xl border border-border-color text-text-gray hover:bg-surface-color hover:text-text-color transition-colors"
          >
            <X size={15} />
          </button>
        </div>

        {/* Body */}
        <div className="flex-1 overflow-y-auto px-5 py-5">
          {isCorrected ? (
            isEditing ? (
              <EditCorrectionForm
                correctionRequest={correctionRequest}
                onCancel={onCancelEdit}
                onSaved={onSaved}
              />
            ) : (
              <CorrectionSentPanel correctionRequest={correctionRequest} onEdit={onEdit} />
            )
          ) : (
            <SendCorrectionForm correctionRequest={correctionRequest} />
          )}
        </div>
      </div>
    </>
  );
}
