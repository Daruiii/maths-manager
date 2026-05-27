import { Link } from '@inertiajs/react';
import { UserCheck, LogIn } from 'lucide-react';
import { TeacherInvitation, User } from '@/types/models';
import Button from '@/Components/Common/UI/Button';
import UserAvatar from '@/Components/Common/Avatar/UserAvatar';

interface Props {
  teacher: User | null;
  invitation: TeacherInvitation | null;
  isAuthenticated: boolean;
  onJoin: () => void;
  loginUrl: string;
}

export default function ValidInvitation({
  teacher,
  invitation,
  isAuthenticated,
  onJoin,
  loginUrl,
}: Props) {
  const teacherName = `${teacher?.first_name} ${teacher?.last_name}`;
  const remainingSlots = invitation ? invitation.max_uses - invitation.current_uses : 0;

  return (
    <>
      <UserAvatar user={teacher} size="xl" className="mx-auto border-4 border-teacher-color/30" />

      <div className="space-y-1">
        <p className="text-text-gray text-sm font-comfortaa">Vous êtes invité(e) à rejoindre</p>
        <h1 className="text-2xl font-comfortaa-bold text-text-color">La classe de {teacherName}</h1>
        {invitation && (
          <p className="text-xs text-text-gray">
            {remainingSlots} place{remainingSlots > 1 ? 's' : ''} restante
            {remainingSlots > 1 ? 's' : ''}
          </p>
        )}
      </div>

      {isAuthenticated ? (
        <Button icon={UserCheck} iconSize={18} className="w-full" onClick={onJoin}>
          Rejoindre la classe
        </Button>
      ) : (
        <div className="flex flex-col gap-3">
          <p className="text-text-gray text-sm">Connectez-vous pour rejoindre la classe.</p>
          <Link href={loginUrl}>
            <Button icon={LogIn} iconSize={18} className="w-full">
              Se connecter
            </Button>
          </Link>
        </div>
      )}
    </>
  );
}
