import React, { useEffect } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputLabel from '@/Components/Common/Form/InputLabel';
import TextInput from '@/Components/Common/Form/TextInput';
import Button from '@/Components/Common/UI/Button';
import AvatarInput from '@/Components/Common/Avatar/AvatarInput';

export default function Register() {
  const { data, setData, post, processing, errors, reset } = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    avatar: null as File | null,
  });

  useEffect(() => {
    return () => {
      reset('password', 'password_confirmation');
    };
  }, []);

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('register'));
  };

  return (
    <GuestLayout>
      <Head title="Inscription" />

      <div className="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 px-6 py-8 sm:px-10 sm:py-10 w-full max-w-lg">
        <div className="mb-8 text-center sm:text-left">
          <h1 className="text-2xl font-comfortaa-bold text-tertiary-color dark:text-tertiary-color">
            Créer un compte
          </h1>
          <p className="text-text-gray dark:text-gray-400 text-xs font-comfortaa mt-1">
            Rejoignez-nous pour progresser.
          </p>
        </div>

        <form onSubmit={submit} className="space-y-6" encType="multipart/form-data">
          <div className="flex items-start gap-6">
            <div>
              <AvatarInput
                value={data.avatar}
                onChange={(file) => setData('avatar', file)}
                onRemove={() => setData('avatar', null)}
              />
              {errors.avatar && (
                <p className="mt-1 text-[10px] text-center text-error-color font-comfortaa">
                  {errors.avatar}
                </p>
              )}
            </div>

            <div className="flex-1 pt-1">
              <InputLabel htmlFor="name" value="Nom complet" />
              <TextInput
                id="name"
                name="name"
                value={data.name}
                className="mt-1 block w-full"
                autoComplete="name"
                isFocused={true}
                onChange={(e) => setData('name', e.target.value)}
                required
              />
              {errors.name && (
                <p className="mt-1 text-xs text-error-color font-comfortaa">{errors.name}</p>
              )}
            </div>
          </div>

          <div className="space-y-4">
            <div>
              <InputLabel htmlFor="email" value="Adresse E-mail" />
              <TextInput
                id="email"
                type="email"
                name="email"
                value={data.email}
                className="mt-1 block w-full"
                autoComplete="username"
                onChange={(e) => setData('email', e.target.value)}
                required
              />
              {errors.email && (
                <p className="mt-1 text-xs text-error-color font-comfortaa">{errors.email}</p>
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
                autoComplete="new-password"
                onChange={(e) => setData('password', e.target.value)}
                required
              />
              {errors.password && (
                <p className="mt-1 text-xs text-error-color font-comfortaa">{errors.password}</p>
              )}
            </div>

            <div>
              <InputLabel htmlFor="password_confirmation" value="Confirmation du mot de passe" />
              <TextInput
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                value={data.password_confirmation}
                className="mt-1 block w-full"
                autoComplete="new-password"
                onChange={(e) => setData('password_confirmation', e.target.value)}
                required
              />
              {errors.password_confirmation && (
                <p className="mt-1 text-xs text-error-color font-comfortaa">
                  {errors.password_confirmation}
                </p>
              )}
            </div>
          </div>

          <div className="pt-4">
            <Button className="w-full justify-center" disabled={processing}>
              S'inscrire
            </Button>
          </div>

          <div className="mt-4 text-center">
            <p className="text-sm text-text-gray dark:text-gray-300 font-comfortaa">
              Déjà un compte ?{' '}
              <Link
                href={route('login')}
                className="font-comfortaa-bold text-tertiary-color hover:text-tertiary-color/80 underline decoration-2 decoration-tertiary-color/30 underline-offset-4 hover:bg-tertiary-color/5 rounded px-1 transition-all"
              >
                Se connecter
              </Link>
            </p>
          </div>
        </form>
      </div>
    </GuestLayout>
  );
}
