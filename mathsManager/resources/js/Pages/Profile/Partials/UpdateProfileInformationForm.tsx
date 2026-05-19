import { useForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import { PageProps, User } from '@/types';
import InputLabel from '@/Components/Common/Form/InputLabel';
import TextInput from '@/Components/Common/Form/TextInput';
import Button from '@/Components/Common/UI/Button';
import AvatarInput from '@/Components/Common/Avatar/AvatarInput';
import VerifyEmailAlert from '@/Components/Features/Auth/VerifyEmailAlert';
import { CheckCircle } from 'lucide-react';

export default function UpdateProfileInformationForm({
  mustVerifyEmail,
  className = '',
}: {
  mustVerifyEmail?: boolean;
  className?: string;
}) {
  const user = usePage<PageProps>().props.auth.user as User;

  // Un professeur validé ne peut plus changer ces informations
  const isTeacherBlocked = user.role === 'teacher' && user.status === 'active';

  const { data, setData, post, errors, processing, reset, transform } = useForm({
    _method: 'PATCH' as const,
    first_name: user?.first_name || '',
    last_name: user?.last_name || '',
    email: user?.email || '',
    avatar: null as File | null,
    remove_avatar: 'false',
  });

  // Si prof bloqué : retirer les champs identité du payload avant envoi
  transform((formData) => {
    if (!isTeacherBlocked) return formData;
    const { first_name: _fn, last_name: _ln, email: _em, ...rest } = formData;
    return rest;
  });

  const handleRemoveAvatar = () => {
    setData((prevData) => ({
      ...prevData,
      avatar: null,
      remove_avatar: 'true',
    }));
  };

  const submit: FormEventHandler = (e) => {
    e.preventDefault();
    post(route('profile.update'), {
      preserveScroll: true,
      forceFormData: true,
      onSuccess: () => {
        // Reset avatar fields to reflect that the change is persisted
        reset('avatar', 'remove_avatar');
      },
    });
  };

  return (
    <section className={className}>
      <div className="mb-4">
        <p className="text-sm text-text-gray">
          Mettez à jour les informations de votre profil et votre adresse e-mail.
        </p>
        {isTeacherBlocked && (
          <p className="mt-2 text-sm text-tertiary-color bg-tertiary-color/10 p-3 rounded-lg flex items-center gap-2 font-comfortaa">
            En tant que professeur validé, votre identité (nom, prénom, email) ne peut plus être
            modifiée pour des raisons de sécurité.
          </p>
        )}
      </div>

      <form onSubmit={submit} className="space-y-6">
        {/* AVATAR SECTION */}
        <div className="flex items-start gap-6">
          <AvatarInput
            user={user}
            value={data.avatar}
            isRemoved={data.remove_avatar === 'true'}
            onChange={(file) => {
              setData((prevData) => ({
                ...prevData,
                avatar: file,
                remove_avatar: 'false',
              }));
            }}
            onRemove={handleRemoveAvatar}
          />
        </div>

        {/* NAME SPLIT */}
        <div className="flex gap-4">
          <div className="w-1/2">
            <InputLabel htmlFor="first_name" value="Prénom" />
            <TextInput
              id="first_name"
              className={`mt-1 block w-full ${isTeacherBlocked ? 'opacity-50 cursor-not-allowed bg-surface-color' : ''}`}
              value={data.first_name}
              onChange={(e) => setData('first_name', e.target.value)}
              required
              disabled={isTeacherBlocked}
              isFocused={!isTeacherBlocked}
              autoComplete="given-name"
            />
            {errors.first_name && (
              <p className="mt-1 text-xs text-error-color font-comfortaa">{errors.first_name}</p>
            )}
          </div>
          <div className="w-1/2">
            <InputLabel htmlFor="last_name" value="Nom" />
            <TextInput
              id="last_name"
              className={`mt-1 block w-full ${isTeacherBlocked ? 'opacity-50 cursor-not-allowed bg-surface-color' : ''}`}
              value={data.last_name}
              onChange={(e) => setData('last_name', e.target.value)}
              required
              disabled={isTeacherBlocked}
              autoComplete="family-name"
            />
            {errors.last_name && (
              <p className="mt-1 text-xs text-error-color font-comfortaa">{errors.last_name}</p>
            )}
          </div>
        </div>

        {/* EMAIL */}
        <div>
          <InputLabel htmlFor="email" value="Email" />

          <TextInput
            id="email"
            type="email"
            className={`mt-1 block w-full ${isTeacherBlocked ? 'opacity-50 cursor-not-allowed bg-surface-color' : ''}`}
            value={data.email}
            onChange={(e) => setData('email', e.target.value)}
            required
            disabled={isTeacherBlocked}
            autoComplete="username"
          />
          {errors.email && (
            <p className="mt-1 text-xs text-error-color font-comfortaa">{errors.email}</p>
          )}

          {mustVerifyEmail && user.email_verified_at === null && <VerifyEmailAlert />}

          {user.email_verified_at !== null && (
            <p className="mt-2 text-sm text-success-color flex items-center gap-1">
              <CheckCircle aria-hidden="true" className="h-4 w-4" />
              Email vérifié
            </p>
          )}
        </div>

        <div className="flex items-center gap-4">
          <Button disabled={processing}>Enregistrer</Button>
        </div>
      </form>
    </section>
  );
}
