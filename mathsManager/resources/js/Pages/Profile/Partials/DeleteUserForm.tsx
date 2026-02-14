import { useRef, useState, SyntheticEvent } from 'react';
import DangerButton from '@/Components/Common/Form/DangerButton';
import InputLabel from '@/Components/Common/Form/InputLabel';
import Modal from '@/Components/Common/UI/Modal';
import SecondaryButton from '@/Components/Common/Form/SecondaryButton';
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
      <header>
        <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
          Supprimer le compte
        </h2>

        <p className="mt-1 text-sm text-text-gray dark:text-gray-400">
          Une fois votre compte supprimé, toutes ses ressources et données seront définitivement
          effacées.
        </p>
      </header>

      <DangerButton onClick={confirmUserDeletion}>Supprimer le compte</DangerButton>

      <Modal show={confirmingUserDeletion} onClose={closeModal}>
        <form onSubmit={deleteUser} className="p-6">
          <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
            Êtes-vous sûr de vouloir supprimer votre compte ?
          </h2>

          <p className="mt-1 text-sm text-text-gray dark:text-gray-400">
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
            <SecondaryButton onClick={closeModal}>Annuler</SecondaryButton>

            <DangerButton className="ml-3" disabled={processing}>
              Supprimer le compte
            </DangerButton>
          </div>
        </form>
      </Modal>
    </section>
  );
}
