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
  status,
  className = '',
}: {
  mustVerifyEmail?: boolean;
  status?: string;
  className?: string;
}) {
  const user = usePage<PageProps>().props.auth.user as User;

  const { data, setData, post, errors, processing, recentlySuccessful, reset } = useForm({
    _method: 'PATCH',
    name: user?.name || '',
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

        {/* NAME */}
        <div>
          <InputLabel htmlFor="name" value="Nom" />

          <TextInput
            id="name"
            className="mt-1 block w-full"
            value={data.name}
            onChange={(e) => setData('name', e.target.value)}
            required
            isFocused
            autoComplete="name"
          />
          {errors.name && (
            <p className="mt-1 text-xs text-error-color font-comfortaa">{errors.name}</p>
          )}
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

          {(recentlySuccessful || status === 'profile-updated') && (
            <div className="flex items-center gap-2 text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-3 py-1 rounded-md">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                className="h-4 w-4"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fillRule="evenodd"
                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                  clipRule="evenodd"
                />
              </svg>
              Modifications enregistrées !
            </div>
          )}
        </div>
      </form>
    </section>
  );
}
