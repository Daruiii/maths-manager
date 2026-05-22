import { useState } from 'react';
import { TeacherInvitation } from '@/types/models';
import { Copy, Check, Link } from 'lucide-react';

interface Props {
  invitations: TeacherInvitation[];
  onConfigure: () => void;
}

export default function InvitationLinkCompact({ invitations, onConfigure }: Props) {
  const [copied, setCopied] = useState(false);
  const activeCount = invitations.length;
  const primaryInvitation = invitations[0] ?? null;
  const isMulti = activeCount > 1;

  const inviteUrl = primaryInvitation
    ? `${window.location.origin}/join/${primaryInvitation.code}`
    : null;

  const totalUses = invitations.reduce((sum, inv) => sum + inv.current_uses, 0);
  const totalMax = invitations.reduce((sum, inv) => sum + inv.max_uses, 0);

  const handleCopy = async () => {
    if (!inviteUrl) return;
    await navigator.clipboard.writeText(inviteUrl);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  if (!primaryInvitation) {
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

  const progress = isMulti
    ? (totalUses / totalMax) * 100
    : (primaryInvitation.current_uses / primaryInvitation.max_uses) * 100;

  return (
    <div className="flex items-center gap-2 min-w-0">
      <button
        onClick={isMulti ? onConfigure : handleCopy}
        className="group relative flex items-center gap-2 border border-border-color rounded-xl px-3 py-2 overflow-hidden cursor-pointer min-w-0 outline-none focus-visible:ring-2 focus-visible:ring-teacher-color/50"
        title={isMulti ? "Gérer les liens d'invitation" : "Copier le lien d'invitation"}
      >
        <div
          className="absolute inset-0 bg-teacher-color/15 transition-all"
          style={{ width: `${progress}%` }}
        />
        <div className="absolute inset-0 bg-surface-color opacity-0 group-hover:opacity-100 transition-opacity" />
        <Link
          size={14}
          className="relative text-text-gray group-hover:text-teacher-color transition-colors shrink-0"
        />
        <span className="relative text-sm text-text-color font-mono truncate min-w-0">
          {isMulti ? `${activeCount} liens actifs` : inviteUrl}
        </span>
        <span className="relative text-xs text-teacher-color font-bold shrink-0 bg-teacher-color/10 px-1.5 py-0.5 rounded-md">
          {isMulti
            ? `${totalUses}/${totalMax}`
            : `${primaryInvitation.current_uses}/${primaryInvitation.max_uses}`}
        </span>
        {!isMulti &&
          (copied ? (
            <Check size={14} className="relative text-success-color shrink-0" />
          ) : (
            <Copy
              size={14}
              className="relative text-text-gray group-hover:text-text-color transition-colors shrink-0"
            />
          ))}
      </button>

      <button
        onClick={onConfigure}
        title="Gérer les liens"
        className="flex items-center justify-center shrink-0 text-sm text-text-gray border border-border-color rounded-xl p-2 hover:bg-surface-color transition-colors"
      >
        <Link size={16} />
      </button>
    </div>
  );
}
