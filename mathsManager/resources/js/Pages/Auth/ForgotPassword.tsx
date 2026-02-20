import { Head, useForm, Link } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputLabel from '@/Components/Common/Form/InputLabel';
import TextInput from '@/Components/Common/Form/TextInput';
import Button from '@/Components/Common/UI/Button';
import FlashToast from '@/Components/Common/UI/FlashToast';

export default function ForgotPassword({ status }: { status?: string }) {
  const { data, setData, post, processing, errors } = useForm({
    email: '',
  });

  const submit: FormEventHandler = (e) => {
    e.preventDefault();

    post(route('password.email'));
  };

  return (
    <GuestLayout>
      <Head title="Mot de passe oublié" />

      <div className="bg-secondary-color p-8 rounded-2xl shadow-xl border-b-4 border-border-color">
        <h2 className="text-2xl font-bold text-text-color mb-4 text-center font-comfortaa-bold">
          Mot de passe oublié ?
        </h2>

        <div className="mb-6 text-sm text-text-gray text-center">
          Pas de souci. Indiquez simplement votre adresse e-mail et nous vous enverrons un lien de
          réinitialisation de mot de passe.
        </div>

        {status && (
          <div className="mb-4">
            <FlashToast message={status} type="success" onClose={() => {}} />
          </div>
        )}

        <form onSubmit={submit}>
          <div>
            <InputLabel htmlFor="email" value="Email" />
            <TextInput
              id="email"
              type="email"
              name="email"
              value={data.email}
              className="mt-1 block w-full"
              isFocused={true}
              onChange={(e) => setData('email', e.target.value)}
            />
            {errors.email && (
              <div className="mt-2 text-sm text-error-color font-medium">{errors.email}</div>
            )}
          </div>

          <div className="flex items-center justify-end mt-6">
            <Button
              className="w-full justify-center"
              disabled={processing}
              variant="primary"
              size="lg"
            >
              Envoyer le lien
            </Button>
          </div>

          <div className="mt-6 text-center">
            <p className="text-sm text-text-gray font-comfortaa">
              <Link
                href={route('login')}
                className="font-comfortaa-bold text-tertiary-color hover:text-tertiary-color/80 underline decoration-2 decoration-tertiary-color/30 underline-offset-4 hover:bg-tertiary-color/5 rounded transition-all"
              >
                ← Retour à la connexion
              </Link>
            </p>
          </div>
        </form>
      </div>
    </GuestLayout>
  );
}
