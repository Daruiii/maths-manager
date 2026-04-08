import { useState } from 'react';
import { RotateCcw, AlertTriangle } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import Modal from '@/Components/Common/UI/Modal';

interface Props {
  itemCount: number;
  onReset: () => void;
}

export default function DmBuilderActions({ itemCount, onReset }: Props) {
  const [confirmOpen, setConfirmOpen] = useState(false);

  if (itemCount === 0) return null;

  return (
    <>
      <div className="flex items-center gap-2">
        <span className="text-xs bg-teacher-color/10 text-teacher-color px-2 py-1 rounded-full font-medium">
          {itemCount} exercice{itemCount > 1 ? 's' : ''}
        </span>
        <Button size="sm" variant="ghost" onClick={() => setConfirmOpen(true)}>
          <RotateCcw size={13} />
          Réinitialiser
        </Button>
      </div>

      <Modal show={confirmOpen} maxWidth="sm" onClose={() => setConfirmOpen(false)}>
        <div className="p-6 space-y-4">
          <div className="flex items-center gap-3">
            <div className="flex-shrink-0 w-9 h-9 rounded-full bg-error-color/10 flex items-center justify-center">
              <AlertTriangle size={18} className="text-error-color" />
            </div>
            <div>
              <p className="font-comfortaa-bold text-text-color text-sm">Réinitialiser le DM ?</p>
              <p className="text-xs text-text-gray mt-0.5">
                Les {itemCount} exercice{itemCount > 1 ? 's' : ''} et les modifications du titre
                seront perdus.
              </p>
            </div>
          </div>
          <div className="flex justify-end gap-2 pt-2">
            <Button size="sm" variant="ghost" onClick={() => setConfirmOpen(false)}>
              Annuler
            </Button>
            <Button
              size="sm"
              variant="ghost"
              className="text-error-color hover:text-error-color hover:bg-error-color/10"
              onClick={() => {
                onReset();
                setConfirmOpen(false);
              }}
            >
              Réinitialiser
            </Button>
          </div>
        </div>
      </Modal>
    </>
  );
}
