import { usePage, router } from '@inertiajs/react';
import { PageProps } from '@/types';

interface VerifyEmailAlertProps {
  className?: string;
}

export default function VerifyEmailAlert({ className = '' }: VerifyEmailAlertProps) {
  const { auth, status } = usePage<PageProps>().props;
  const user = auth.user;

  if (user?.email_verified_at !== null) {
    return null;
  }

  const sendVerification = () => {
    router.post(route('verification.send'));
  };

  return (
    <div className={`mt-2 ${className}`}>
      <p className="text-sm text-gray-800 dark:text-gray-200">
        Votre adresse e-mail n'a pas encore été vérifiée.
        <button
          type="button"
          onClick={sendVerification}
          className="ml-2 underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Cliquez ici pour renvoyer l'e-mail de vérification.
        </button>
      </p>

      {status === 'verification-link-sent' && (
        <p className="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
          Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
        </p>
      )}
    </div>
  );
}
