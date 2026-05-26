import { useState } from 'react';
import { Upload, X } from 'lucide-react';
import Modal from '@/Components/Common/UI/Modal';
import AssignmentActionDock from '@/Components/Features/Assignments/AssignmentActionDock';
import CopySubmitSection from '@/Components/Features/Assignments/CopySubmitSection';

interface Props {
  onSubmit: (e: React.SyntheticEvent) => void;
  sessionToken: string | null;
  onTokenChange: (token: string | null) => void;
  message: string;
  onMessageChange: (msg: string) => void;
  submitting: boolean;
  uploadError?: string | null;
  label?: string;
  description?: string;
}

export default function CopySubmitModal({
  onSubmit,
  sessionToken,
  onTokenChange,
  message,
  onMessageChange,
  submitting,
  uploadError,
  label = 'Envoyer ma copie',
  description = 'Prêt à remettre ton travail ? Ajoute tes photos et ton message.',
}: Props) {
  const [open, setOpen] = useState(false);

  return (
    <>
      <AssignmentActionDock
        label={label}
        mobileLabel="Envoyer"
        description={description}
        icon={Upload}
        onClick={() => setOpen(true)}
      />

      {/* Modal */}
      <Modal show={open} maxWidth="lg" onClose={() => setOpen(false)}>
        <div>
          {/* Header */}
          <div className="flex items-start justify-between gap-4 border-b border-border-color px-6 pt-6 pb-4">
            <div>
              <p className="text-[10px] font-comfortaa-bold uppercase tracking-widest text-text-gray">
                Remise de copie
              </p>
              <h2 className="mt-0.5 font-cmu-serif text-xl text-text-color">Envoyer ta copie</h2>
            </div>
            <button
              type="button"
              onClick={() => setOpen(false)}
              className="mt-1 flex items-center gap-1 rounded-lg px-2 py-1 text-xs text-text-gray hover:bg-surface-color hover:text-text-color transition-colors"
            >
              <X size={13} />
              <span className="font-comfortaa">Fermer</span>
            </button>
          </div>

          {/* Body */}
          <form onSubmit={onSubmit} className="px-6 py-5 space-y-4">
            <CopySubmitSection
              sessionToken={sessionToken}
              onTokenChange={onTokenChange}
              message={message}
              onMessageChange={onMessageChange}
              submitting={submitting}
              uploadError={uploadError}
            />
          </form>
        </div>
      </Modal>
    </>
  );
}
