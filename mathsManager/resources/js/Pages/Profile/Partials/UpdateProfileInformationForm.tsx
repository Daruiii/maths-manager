import { useForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import { PageProps, User } from '@/types';
import InputLabel from '@/Components/Common/Form/InputLabel';
import TextInput from '@/Components/Common/Form/TextInput';
import Button from '@/Components/Common/UI/Button';
import AvatarInput from '@/Components/Common/Avatar/AvatarInput';
import VerifyEmailAlert from '@/Components/Features/Auth/VerifyEmailAlert';

export default function UpdateProfileInformationForm({
  mustVerifyEmail,
  className = '',
}: {
  mustVerifyEmail?: boolean;
  className?: string;
}) {
  const user = usePage<PageProps>().props.auth.user as User;

  const { data, setData, post, errors, processing, reset } = useForm({
    _method: 'PATCH',
    first_name: user?.first_name || '',
    last_name: user?.last_name || '',
    email: user?.email || '',
    avatar: null as File | null,
    remove_avatar: 'false',
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
        <p className="text-sm text-gray-500 dark:text-gray-400">
          Mettez à jour les informations de votre profil et votre adresse e-mail.
        </p>
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
              className="mt-1 block w-full"
              value={data.first_name}
              onChange={(e) => setData('first_name', e.target.value)}
              required
              isFocused
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
              className="mt-1 block w-full"
              value={data.last_name}
              onChange={(e) => setData('last_name', e.target.value)}
              required
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
            className="mt-1 block w-full"
            value={data.email}
            onChange={(e) => setData('email', e.target.value)}
            required
            autoComplete="username"
          />
          {errors.email && (
            <p className="mt-1 text-xs text-error-color font-comfortaa">{errors.email}</p>
          )}

          {mustVerifyEmail && user.email_verified_at === null && <VerifyEmailAlert />}

          {user.email_verified_at !== null && (
            <p className="mt-2 text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                className="h-4 w-4"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fillRule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                  clipRule="evenodd"
                />
              </svg>
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
