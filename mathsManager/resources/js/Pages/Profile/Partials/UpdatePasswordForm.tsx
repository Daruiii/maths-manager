import { useRef, SyntheticEvent } from 'react';
import InputLabel from '@/Components/Common/Form/InputLabel';
import PrimaryButton from '@/Components/Common/Form/PrimaryButton';
import TextInput from '@/Components/Common/Form/TextInput';
import { useForm } from '@inertiajs/react';
import { Lock } from 'lucide-react';

export default function UpdatePasswordForm({ className = '' }: { className?: string }) {
  const passwordInput = useRef<HTMLInputElement>(null);
  const currentPasswordInput = useRef<HTMLInputElement>(null);

  const { data, setData, errors, put, reset, processing, recentlySuccessful } = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
  });

  const updatePassword = (e: SyntheticEvent) => {
    e.preventDefault();

    put(route('password.update'), {
      preserveScroll: true,
      onSuccess: () => reset(),
      onError: (errors) => {
        if (errors.password) {
          reset('password', 'password_confirmation');
          passwordInput.current?.focus();
        }

        if (errors.current_password) {
          reset('current_password');
          currentPasswordInput.current?.focus();
        }
      },
    });
  };

  return (
    <section className={className}>
      <div className="mb-6">
        <h2 className="text-xl font-comfortaa-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
          <span className="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400">
            <Lock className="h-5 w-5" />
          </span>
          Sécurité & Mot de passe
        </h2>
        <p className="mt-2 text-sm text-gray-500 dark:text-gray-400 ml-11">
          Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester
          sécurisé.
        </p>
      </div>

      <form onSubmit={updatePassword} className="mt-6 space-y-6">
        <div>
          <InputLabel htmlFor="current_password" value="Mot de passe actuel" />

          <TextInput
            id="current_password"
            ref={currentPasswordInput}
            value={data.current_password}
            onChange={(e) => setData('current_password', e.target.value)}
            type="password"
            className="mt-1 block w-full"
            autoComplete="current-password"
          />

          {errors.current_password && (
            <p className="mt-1 text-xs text-error-color font-comfortaa">
              {errors.current_password}
            </p>
          )}
        </div>

        <div>
          <InputLabel htmlFor="password" value="Nouveau mot de passe" />

          <TextInput
            id="password"
            ref={passwordInput}
            value={data.password}
            onChange={(e) => setData('password', e.target.value)}
            type="password"
            className="mt-1 block w-full"
            autoComplete="new-password"
          />
          {errors.password && (
            <p className="mt-1 text-xs text-error-color font-comfortaa">{errors.password}</p>
          )}
        </div>

        <div>
          <InputLabel htmlFor="password_confirmation" value="Confirmer le mot de passe" />

          <TextInput
            id="password_confirmation"
            value={data.password_confirmation}
            onChange={(e) => setData('password_confirmation', e.target.value)}
            type="password"
            className="mt-1 block w-full"
            autoComplete="new-password"
          />
          {errors.password_confirmation && (
            <p className="mt-1 text-xs text-error-color font-comfortaa">
              {errors.password_confirmation}
            </p>
          )}
        </div>

        <div className="flex items-center gap-4">
          <PrimaryButton disabled={processing}>Enregistrer</PrimaryButton>

          {recentlySuccessful && (
            <p className="text-sm text-gray-600 dark:text-gray-400 transition ease-in-out">
              Enregistré.
            </p>
          )}
        </div>
      </form>
    </section>
  );
}
