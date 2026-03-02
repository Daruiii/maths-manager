import { ReactNode, useMemo } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { TeacherInvitation, User } from '@/types/models';
import { PageProps } from '@/types';
import { AlertCircle, CheckCircle, RefreshCw } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import UserAvatar from '@/Components/Common/UI/UserAvatar';
import StatusCard from '@/Components/Common/UI/StatusCard';
import GuestLayout from '@/Layouts/GuestLayout';
import ValidInvitation from '@/Pages/Invitation/Partials/ValidInvitation';
import { route } from 'ziggy-js';

interface Props {
  invitation: TeacherInvitation | null;
  teacher: User | null;
  isValid: boolean;
  alreadyJoined: boolean;
  hasOtherTeacher: boolean;
  code: string;
}

type State = 'invalid' | 'already_joined' | 'switch_teacher' | 'valid';

export default function Join({
  invitation,
  teacher,
  isValid,
  alreadyJoined,
  hasOtherTeacher,
  code,
}: Props) {
  const { auth } = usePage<PageProps>().props;
  const isAuthenticated = !!auth.user;

  const state = useMemo((): State => {
    if (!isValid) return 'invalid';
    if (alreadyJoined) return 'already_joined';
    if (hasOtherTeacher && isAuthenticated) return 'switch_teacher';
    return 'valid';
  }, [isValid, alreadyJoined, hasOtherTeacher, isAuthenticated]);

  const handleJoin = () => router.post(route('invitation.accept', code));
  const teacherName = `${teacher?.first_name} ${teacher?.last_name}`;
  const loginUrl = `${route('login')}?redirect=${encodeURIComponent(route('invitation.join', code))}`;

  const content: Record<State, ReactNode> = {
    invalid: (
      <StatusCard
        type="error"
        icon={AlertCircle}
        title="Lien invalide ou expiré"
        description="Ce lien d'invitation n'est plus valide. Demandez à votre professeur d'en générer un nouveau."
      >
        <Link href={route('home')}>
          <Button variant="secondary" className="mt-2 w-full">
            Retour à l'accueil
          </Button>
        </Link>
      </StatusCard>
    ),

    already_joined: (
      <StatusCard
        type="success"
        icon={CheckCircle}
        title="Vous êtes déjà dans cette classe !"
        description={`Vous faites déjà partie de la classe de ${teacherName}.`}
      >
        <Link href={route('home')}>
          <Button variant="secondary" className="mt-2 w-full">
            Retour à l'accueil
          </Button>
        </Link>
      </StatusCard>
    ),

    switch_teacher: (
      <StatusCard
        type="warning"
        icon={RefreshCw}
        title="Changer de classe ?"
        header={
          <UserAvatar
            user={teacher}
            size="xl"
            className="mx-auto border-4 border-teacher-color/30"
          />
        }
        description={
          <>
            Vous êtes déjà rattaché(e) à un autre professeur.
            <br />
            Voulez-vous rejoindre la classe de{' '}
            <strong className="text-text-color">{teacherName}</strong> à la place ?
          </>
        }
      >
        <div className="flex flex-col gap-2">
          <Button icon={RefreshCw} iconSize={18} className="w-full" onClick={handleJoin}>
            Changer de classe
          </Button>
          <Link href={route('home')}>
            <Button variant="secondary" className="w-full">
              Annuler
            </Button>
          </Link>
        </div>
      </StatusCard>
    ),

    valid: (
      <ValidInvitation
        teacher={teacher}
        invitation={invitation}
        isAuthenticated={isAuthenticated}
        onJoin={handleJoin}
        loginUrl={loginUrl}
      />
    ),
  };

  return (
    <GuestLayout>
      <Head title="Rejoindre une classe" />
      <div className="bg-secondary-color border border-border-color rounded-3xl shadow-xl p-8 text-center space-y-6">
        {content[state]}
      </div>
    </GuestLayout>
  );
}
