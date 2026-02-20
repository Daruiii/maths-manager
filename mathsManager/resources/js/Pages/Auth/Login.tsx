import React, { useEffect } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputLabel from '@/Components/Common/Form/InputLabel';
import TextInput from '@/Components/Common/Form/TextInput';
import Checkbox from '@/Components/Common/Form/Checkbox';
import Button from '@/Components/Common/UI/Button';
import GoogleButton from '@/Components/Features/Auth/GoogleButton';

export default function Login({ canResetPassword }: { canResetPassword?: boolean }) {
  const { data, setData, post, processing, errors, reset } = useForm({
    email: '',
    password: '',
    remember: false,
  });

  useEffect(() => {
    return () => {
      reset('password');
    };
  }, []);

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('login'));
  };

  return (
    <GuestLayout>
      <Head title="Connexion" />

      <div className="bg-secondary-color rounded-[2rem] shadow-sm border border-border-color px-6 py-8 sm:px-10 sm:py-10">
        <div className="mb-6 text-center">
          <h1 className="text-2xl font-comfortaa-bold text-tertiary-color dark:text-tertiary-color">
            Bon retour !
          </h1>
          <p className="text-text-gray text-sm font-comfortaa mt-1">
            Connectez-vous à votre espace.
          </p>
        </div>

        <form onSubmit={submit} className="space-y-4">
          <div>
            <InputLabel htmlFor="email" value="Adresse E-mail" />
            <TextInput
              id="email"
              type="email"
              name="email"
              value={data.email}
              className="mt-1 block w-full"
              autoComplete="username"
              isFocused={true}
              onChange={(e) => setData('email', e.target.value)}
            />
            {errors.email && (
              <p className="mt-2 text-sm text-error-color font-comfortaa">{errors.email}</p>
            )}
          </div>

          <div>
            <InputLabel htmlFor="password" value="Mot de passe" />
            <TextInput
              id="password"
              type="password"
              name="password"
              value={data.password}
              className="mt-1 block w-full"
              autoComplete="current-password"
              onChange={(e) => setData('password', e.target.value)}
            />
            {errors.password && (
              <p className="mt-2 text-sm text-error-color font-comfortaa">{errors.password}</p>
            )}
          </div>

          <div className="flex items-center justify-between mt-4">
            <label className="flex items-center">
              <Checkbox
                name="remember"
                checked={data.remember}
                onChange={(e) => setData('remember', e.target.checked)}
              />
              <span className="ms-2 text-sm text-text-gray font-comfortaa">Se souvenir de moi</span>
            </label>

            {canResetPassword && (
              <Link
                href={route('password.request')}
                className="underline text-sm text-text-gray hover:text-tertiary-color rounded-md transition-colors font-comfortaa"
              >
                Mot de passe oublié ?
              </Link>
            )}
          </div>

          <div className="pt-2 gap-3 flex flex-col">
            <Button className="w-full justify-center" disabled={processing}>
              Se connecter
            </Button>

            <div className="relative flex py-1 items-center">
              <div className="flex-grow border-t border-border-color"></div>
              <span className="flex-shrink mx-4 text-text-gray text-xs font-comfortaa uppercase">
                OU
              </span>
              <div className="flex-grow border-t border-border-color"></div>
            </div>

            <GoogleButton />
          </div>

          <div className="mt-6 text-center">
            <p className="text-sm text-text-gray font-comfortaa">
              Pas encore de compte ?{' '}
              <Link
                href={route('register')}
                className="font-comfortaa-bold text-tertiary-color hover:text-tertiary-color/80 underline decoration-2 decoration-tertiary-color/30 underline-offset-4 hover:bg-tertiary-color/5 rounded transition-all"
              >
                S'inscrire
              </Link>
            </p>
          </div>
        </form>
      </div>
    </GuestLayout>
  );
}
