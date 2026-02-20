import { useRef, useState, SyntheticEvent } from 'react';
import Button from '@/Components/Common/UI/Button';
import InputLabel from '@/Components/Common/Form/InputLabel';
import Modal from '@/Components/Common/UI/Modal';
import TextInput from '@/Components/Common/Form/TextInput';
import { useForm } from '@inertiajs/react';

export default function DeleteUserForm({ className = '' }: { className?: string }) {
  const [confirmingUserDeletion, setConfirmingUserDeletion] = useState(false);
  const passwordInput = useRef<HTMLInputElement>(null);

  const {
    data,
    setData,
    delete: destroy,
    processing,
    reset,
    errors,
    clearErrors,
  } = useForm({
    password: '',
    confirmation: '',
  });

  const confirmUserDeletion = () => {
    setConfirmingUserDeletion(true);
  };

  const deleteUser = (e: SyntheticEvent) => {
    e.preventDefault();

    destroy(route('profile.destroy'), {
      preserveScroll: true,
      onSuccess: () => closeModal(),
      onError: () => passwordInput.current?.focus(),
      onFinish: () => reset(),
    });
  };

  const closeModal = () => {
    setConfirmingUserDeletion(false);
    clearErrors();
    reset();
  };

  return (
    <section className={`space-y-6 ${className}`}>
      <div className="text-sm text-text-gray">
        Une fois votre compte supprimé, toutes ses ressources et données seront définitivement
        effacées.
      </div>

      <Button variant="danger" onClick={confirmUserDeletion}>
        Supprimer le compte
      </Button>

      <Modal show={confirmingUserDeletion} onClose={closeModal}>
        <form onSubmit={deleteUser} className="p-6">
          <h2 className="text-lg font-medium text-text-color">
            Êtes-vous sûr de vouloir supprimer votre compte ?
          </h2>

          <p className="mt-1 text-sm text-text-gray">
            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement
            effacées. Veuillez entrer <strong>supprimer mon compte</strong> pour confirmer.
          </p>

          <div className="mt-6">
            <InputLabel
              htmlFor="confirmation"
              value='Tapez "supprimer mon compte"'
              className="sr-only"
            />

            <TextInput
              id="confirmation"
              type="text"
              name="confirmation"
              value={data.confirmation}
              onChange={(e) => setData('confirmation', e.target.value)}
              className="mt-1 block w-full"
              isFocused
              placeholder='Tapez "supprimer mon compte"'
            />
            {errors.confirmation && (
              <p className="mt-1 text-xs text-error-color font-comfortaa">{errors.confirmation}</p>
            )}
          </div>

          <div className="mt-6 flex justify-end">
            <Button variant="secondary" onClick={closeModal}>
              Annuler
            </Button>

            <Button variant="danger" className="ml-3" disabled={processing}>
              Supprimer le compte
            </Button>
          </div>
        </form>
      </Modal>
    </section>
  );
}
