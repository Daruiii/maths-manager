import React, { useEffect, useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputLabel from '@/Components/Auth/InputLabel';
import TextInput from '@/Components/Auth/TextInput';
import PrimaryButton from '@/Components/Auth/PrimaryButton';
import RegisterAvatarSection from '@/Components/Avatar/RegisterAvatarSection';
import AvatarCropModal from '@/Components/Avatar/AvatarCropModal';

export default function Register() {
  const { data, setData, post, processing, errors, reset } = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    avatar: null as File | null,
  });

  const [imageSrc, setImageSrc] = useState<string | null>(null);
  const [showCropper, setShowCropper] = useState(false);

  useEffect(() => {
    return () => {
      reset('password', 'password_confirmation');
      if (imageSrc && imageSrc.startsWith('blob:')) {
        URL.revokeObjectURL(imageSrc);
      }
    };
  }, []);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files.length > 0) {
      const file = e.target.files[0];
      if (imageSrc && imageSrc.startsWith('blob:')) {
        URL.revokeObjectURL(imageSrc);
      }
      const url = URL.createObjectURL(file);
      setImageSrc(url);
      setShowCropper(true);

      // Allow re-selection of the same file
      e.target.value = '';
    }
  };

  const handleCropSave = (croppedFile: File | null) => {
    if (croppedFile) {
      setData('avatar', croppedFile);
    }
    setShowCropper(false);
  };

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('register'));
  };

  return (
    <GuestLayout>
      <Head title="Inscription" />

      <div className="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 px-6 py-8 sm:px-10 sm:py-10 w-full max-w-lg">
        <div className="mb-8 text-center sm:text-left">
          <h1 className="text-2xl font-comfortaa-bold text-admin-color dark:text-admin-color">
            Créer un compte
          </h1>
          <p className="text-text-gray dark:text-gray-400 text-xs font-comfortaa mt-1">
            Rejoignez-nous pour progresser.
          </p>
        </div>

        <form onSubmit={submit} className="space-y-6" encType="multipart/form-data">
          <div className="flex items-start gap-6">
            <RegisterAvatarSection
              avatarFile={data.avatar}
              onFileChange={handleFileChange}
              onCropClick={() => setShowCropper(true)}
              onRemoveClick={() => {
                setData('avatar', null);
                setImageSrc(null);
              }}
            />

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
              {errors.avatar && (
                <p className="mt-1 text-[10px] text-error-color font-comfortaa">{errors.avatar}</p>
              )}
            </div>
          </div>

          {showCropper && imageSrc && (
            <AvatarCropModal
              imageSrc={imageSrc}
              onClose={() => setShowCropper(false)}
              onSave={handleCropSave}
            />
          )}

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
            <PrimaryButton className="w-full justify-center" disabled={processing}>
              S'inscrire
            </PrimaryButton>
          </div>

          <div className="mt-4 text-center">
            <Link
              href={route('login')}
              className="text-sm text-text-gray dark:text-gray-300 hover:text-admin-color dark:hover:text-admin-color transition-colors font-comfortaa"
            >
              Déjà un compte ?{' '}
              <span className="font-comfortaa-bold underline decoration-admin-color/30 underline-offset-4">
                Se connecter
              </span>
            </Link>
          </div>
        </form>
      </div>
    </GuestLayout>
  );
}
