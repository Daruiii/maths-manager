import { useState } from 'react';
import { TeacherInvitation } from '@/types/models';
import { Copy, Check, RefreshCw, Link } from 'lucide-react';

interface Props {
  invitation: TeacherInvitation | null;
  onConfigure: () => void;
}

export default function InvitationLinkCompact({ invitation, onConfigure }: Props) {
  const [copied, setCopied] = useState(false);

  const inviteUrl = invitation ? `${window.location.origin}/join/${invitation.code}` : null;

  const handleCopy = async () => {
    if (!inviteUrl) return;
    await navigator.clipboard.writeText(inviteUrl);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  if (!invitation) {
    return (
      <button
        onClick={onConfigure}
        className="flex items-center gap-1.5 text-sm text-teacher-color border border-teacher-color/30 bg-teacher-color/10 rounded-xl px-3 py-2 hover:bg-teacher-color/20 transition-colors"
      >
        <Link size={16} />
        <span className="sm:inline font-medium">Générer un lien d'invitation</span>
      </button>
    );
  }

  const progress = (invitation.current_uses / invitation.max_uses) * 100;

  return (
    <div className="flex items-end gap-2">
      <div className="flex flex-col gap-0.5">
        <span className="text-xxs text-text-gray/60 font-medium tracking-wide px-1">
          Lien d'invitation
        </span>
        <button
          onClick={handleCopy}
          className="group relative flex items-center gap-2 border border-border-color rounded-xl px-3 py-2 overflow-hidden cursor-pointer"
        >
          <div
            className="absolute inset-0 bg-teacher-color/15 transition-all"
            style={{ width: `${progress}%` }}
          />
          <div className="absolute inset-0 bg-surface-color opacity-0 group-hover:opacity-100 transition-opacity" />
          <span className="relative text-xs text-text-color font-mono">{inviteUrl}</span>
          <span className="relative text-xxs text-teacher-color font-bold">
            {invitation.current_uses}/{invitation.max_uses}
          </span>
          {copied ? (
            <Check size={14} className="relative text-success-color" />
          ) : (
            <Copy
              size={14}
              className="relative text-text-gray group-hover:text-text-color transition-colors"
            />
          )}
        </button>
      </div>

      <button
        onClick={onConfigure}
        title="Régénérer le lien"
        className="flex items-center justify-center text-sm text-text-gray border border-border-color rounded-xl p-2 hover:bg-surface-color transition-colors"
      >
        <RefreshCw size={16} />
      </button>
    </div>
  );
}
