import { User } from '@/types';
import UserAvatar from '@/Components/Common/UI/UserAvatar';
import Button from '@/Components/Common/UI/Button';
import Bubble from '@/Components/Common/UI/Bubble';
import IconBackgroundContainer from '@/Components/Common/UI/IconBackgroundContainer';
import {
  CheckCircle,
  XCircle,
  MapPin,
  Phone,
  Building2,
  GraduationCap,
  Quote,
  AlignLeft,
  Calendar,
} from 'lucide-react';

interface Props {
  user: User;
  onApprove: (user: User) => void;
  onReject: (user: User) => void;
  onInvite?: (user: User) => void;
}

export default function ApplicantDetails({ user, onApprove, onReject, onInvite }: Props) {
  const isPendingApproval = user.status === 'pending_approval';
  const hasInviteBeenSent = user.calendly_invite_sent;

  return (
    <div className="flex flex-col h-full bg-secondary-color overflow-hidden border-0 shadow-none sm:shadow-sm sm:border sm:border-border-color font-sans rounded-2xl">
      {/* Header : Avatar + Infos + Actions (desktop) */}
      <div className="p-4 border-b-2 border-border-color bg-surface-color/50 flex-shrink-0">
        <div className="flex items-center gap-3">
          <UserAvatar
            user={user}
            size="lg"
            className="ring-2 ring-border-color shadow-sm flex-shrink-0"
          />
          <div className="min-w-0 flex-1">
            <h2 className="text-base sm:text-lg font-black text-text-color truncate tracking-tight">
              {user.first_name} {user.last_name}
            </h2>
            <div className="flex flex-wrap items-center gap-1.5">
              <p className="text-xs font-bold text-text-gray truncate">{user.email}</p>
              {Boolean(hasInviteBeenSent) && (
                <span className="bg-tertiary-color/10 text-tertiary-color font-bold text-xxs uppercase tracking-wider px-1.5 py-0.5 rounded-md border border-tertiary-color/20 flex-shrink-0">
                  Invité
                </span>
              )}
            </div>
          </div>

          {/* Actions desktop — hidden on mobile, shown sm+ */}
          {isPendingApproval && (
            <div className="hidden sm:flex items-center gap-1.5 flex-shrink-0">
              <Button icon={XCircle} variant="danger" size="sm" onClick={() => onReject(user)}>
                Refuser
              </Button>
              <Button
                icon={CheckCircle}
                variant="success"
                size="sm"
                onClick={() => onApprove(user)}
              >
                Valider
              </Button>
              {onInvite && (
                <Button
                  icon={Calendar}
                  variant={hasInviteBeenSent ? 'secondary' : 'teacher'}
                  size="sm"
                  title="Inviter via Calendly"
                  onClick={() => onInvite(user)}
                  className={hasInviteBeenSent ? 'border-teacher-color/30 text-teacher-color' : ''}
                >
                  Inviter
                </Button>
              )}
            </div>
          )}
        </div>
      </div>

      {/* Infos en bulles (toujours visibles) */}
      <div className="p-4 sm:px-6 flex-shrink-0">
        <h3 className="text-sm font-black text-text-color mb-3 inline-block relative">
          <span className="relative z-10">Fiche de renseignements</span>
          <span className="absolute bottom-0.5 left-0 w-full h-2 bg-tertiary-color/20 -z-10 -rotate-1 rounded-sm"></span>
        </h3>
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-2">
          <Bubble icon={MapPin} label="Localisation" value={user.location} className="truncate" />
          <Bubble icon={Phone} label="Contact" value={user.phone} className="truncate" />
          <Bubble icon={GraduationCap} label="Diplôme" value={user.diploma} className="truncate" />
          <Bubble
            icon={Building2}
            label="Niveau"
            value={user.teaching_level}
            className="truncate"
          />
        </div>
      </div>

      {/* Bio (scrollable si longue) */}
      <div className="flex-1 min-h-0 overflow-y-auto custom-scrollbar px-4 sm:px-6 pb-4">
        <h3 className="text-sm font-black text-text-color mb-3 inline-block relative">
          <span className="relative z-10">Présentation du profil</span>
          <span className="absolute bottom-0.5 left-0 w-full h-2 bg-teacher-color/20 -z-10 -rotate-1 rounded-sm"></span>
        </h3>

        <IconBackgroundContainer icon={Quote} iconClassName="text-teacher-color/5">
          <div className="text-text-color text-sm leading-relaxed font-medium">
            {user.bio ? (
              <p className="whitespace-pre-line">{user.bio}</p>
            ) : (
              <div className="flex flex-col items-center justify-center py-4 text-text-gray opacity-70">
                <AlignLeft size={28} className="mb-2" />
                <p className="italic font-normal text-xs">
                  L&apos;utilisateur n&apos;a rédigé aucune présentation.
                </p>
              </div>
            )}
          </div>
        </IconBackgroundContainer>
      </div>

      {/* Footer Actions — Mobile only */}
      {isPendingApproval && (
        <div className="sm:hidden flex-shrink-0 p-3 border-t-2 border-border-color bg-surface-color/50 flex items-center justify-center gap-2">
          <Button icon={XCircle} variant="danger" size="sm" onClick={() => onReject(user)}>
            Refuser
          </Button>
          <Button icon={CheckCircle} variant="success" size="sm" onClick={() => onApprove(user)}>
            Valider
          </Button>
          {onInvite && (
            <Button
              icon={Calendar}
              variant={hasInviteBeenSent ? 'secondary' : 'teacher'}
              size="sm"
              title="Inviter via Calendly"
              onClick={() => onInvite(user)}
              className={hasInviteBeenSent ? 'border-teacher-color/30 text-teacher-color' : ''}
            >
              Inviter
            </Button>
          )}
        </div>
      )}
    </div>
  );
}
