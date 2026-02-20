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
      <p className="text-sm text-text-color">
        Votre adresse e-mail n'a pas encore été vérifiée.
        <button
          type="button"
          onClick={sendVerification}
          className="ml-2 underline text-sm text-text-gray hover:text-text-color rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tertiary-color transition-colors"
        >
          Cliquez ici pour renvoyer l'e-mail de vérification.
        </button>
      </p>

      {status === 'verification-link-sent' && (
        <p className="mt-2 font-medium text-sm text-success-color">
          Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
        </p>
      )}
    </div>
  );
}
