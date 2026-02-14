import React, { useEffect } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputLabel from '@/Components/Common/Form/InputLabel';
import TextInput from '@/Components/Common/Form/TextInput';
import Checkbox from '@/Components/Common/Form/Checkbox';
import PrimaryButton from '@/Components/Common/Form/PrimaryButton';
import GoogleButton from '@/Components/Features/Auth/GoogleButton';

export default function Login({
  status,
  canResetPassword,
}: {
  status?: string;
  canResetPassword?: boolean;
}) {
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

      <div className="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 px-6 py-8 sm:px-10 sm:py-10">
        <div className="mb-6 text-center">
          <h1 className="text-2xl font-comfortaa-bold text-admin-color dark:text-admin-color">
            Bon retour !
          </h1>
          <p className="text-text-gray dark:text-gray-400 text-sm font-comfortaa mt-1">
            Connectez-vous à votre espace.
          </p>
        </div>

        {status && <div className="mb-4 font-medium text-sm text-success-color">{status}</div>}

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
              <span className="ms-2 text-sm text-text-gray dark:text-gray-300 font-comfortaa">
                Se souvenir de moi
              </span>
            </label>

            {canResetPassword && (
              <Link
                href={route('password.request')}
                className="underline text-sm text-text-gray dark:text-gray-300 hover:text-admin-color dark:hover:text-admin-color rounded-md transition-colors font-comfortaa"
              >
                Mot de passe oublié ?
              </Link>
            )}
          </div>

          <div className="pt-2 gap-3 flex flex-col">
            <PrimaryButton className="w-full justify-center" disabled={processing}>
              Se connecter
            </PrimaryButton>

            <div className="relative flex py-1 items-center">
              <div className="flex-grow border-t border-gray-200 dark:border-gray-600"></div>
              <span className="flex-shrink mx-4 text-gray-400 dark:text-gray-500 text-xs font-comfortaa uppercase">
                OU
              </span>
              <div className="flex-grow border-t border-gray-200 dark:border-gray-600"></div>
            </div>

            <GoogleButton />
          </div>

          <div className="mt-6 text-center">
            <Link
              href={route('register')}
              className="text-sm text-text-gray dark:text-gray-300 hover:text-admin-color dark:hover:text-admin-color transition-colors font-comfortaa"
            >
              Pas encore de compte ? <span className="font-comfortaa-bold">S'inscrire</span>
            </Link>
          </div>
        </form>
      </div>
    </GuestLayout>
  );
}
