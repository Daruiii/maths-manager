import { useState } from 'react';
import { router } from '@inertiajs/react';
import { Check, Copy, Link, Plus, Power } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import Modal from '@/Components/Common/UI/Modal';
import type { StudentGroup, TeacherInvitation } from '@/types/models';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  groups: StudentGroup[];
  invitations: TeacherInvitation[];
}

export default function InvitationConfigModal({ isOpen, onClose, groups, invitations }: Props) {
  const [label, setLabel] = useState('');
  const [maxUses, setMaxUses] = useState('1');
  const [groupId, setGroupId] = useState<number | ''>('');
  const [copiedId, setCopiedId] = useState<number | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [disablingId, setDisablingId] = useState<number | null>(null);

  const handleCreate = () => {
    setIsSubmitting(true);
    router.post(
      route('teacher.invitation.configure'),
      {
        label: label.trim() || null,
        max_uses: parseInt(maxUses, 10) || 1,
        group_id: groupId || null,
      },
      {
        preserveScroll: true,
        onSuccess: () => {
          setLabel('');
          setMaxUses('1');
          setGroupId('');
        },
        onFinish: () => setIsSubmitting(false),
      }
    );
  };

  const copyLink = async (invitation: TeacherInvitation) => {
    await navigator.clipboard.writeText(inviteUrl(invitation));
    setCopiedId(invitation.id);
    setTimeout(() => setCopiedId(null), 1800);
  };

  const disableLink = (invitation: TeacherInvitation) => {
    setDisablingId(invitation.id);
    router.patch(
      route('teacher.invitation.disable', invitation.id),
      {},
      {
        preserveScroll: true,
        onFinish: () => setDisablingId(null),
      }
    );
  };

  return (
    <Modal show={isOpen} onClose={onClose} maxWidth="2xl">
      <div className="p-6 space-y-6">
        <div>
          <h2 className="text-lg font-comfortaa-bold text-text-color">Liens d'invitation</h2>
          <p className="text-sm text-text-gray mt-1">
            Créez plusieurs liens actifs pour vos classes, cours ou partages ponctuels.
          </p>
        </div>

        <div className="space-y-2">
          {invitations.length === 0 ? (
            <div className="rounded-2xl border border-dashed border-border-color bg-surface-color px-4 py-6 text-center">
              <Link size={22} className="mx-auto text-text-gray" />
              <p className="mt-2 text-sm font-comfortaa-bold text-text-color">Aucun lien actif</p>
              <p className="mt-1 text-xs text-text-gray">Créez un lien pour inviter vos élèves.</p>
            </div>
          ) : (
            invitations.map((invitation) => (
              <InvitationRow
                key={invitation.id}
                invitation={invitation}
                copied={copiedId === invitation.id}
                disabling={disablingId === invitation.id}
                onCopy={() => copyLink(invitation)}
                onDisable={() => disableLink(invitation)}
              />
            ))
          )}
        </div>

        <div className="rounded-2xl border border-border-color bg-surface-color p-4 space-y-4">
          <div>
            <p className="text-sm font-comfortaa-bold text-text-color">Créer un nouveau lien</p>
            <p className="text-xs text-text-gray mt-0.5">
              Les anciens liens restent actifs tant que vous ne les désactivez pas.
            </p>
          </div>

          <div className="grid gap-3 sm:grid-cols-[1fr_160px]">
            <label className="block">
              <span className="block text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide mb-1.5">
                Libellé
              </span>
              <input
                value={label}
                onChange={(event) => setLabel(event.target.value)}
                placeholder="Ex : Terminale WhatsApp"
                className="w-full rounded-xl border border-border-color bg-secondary-color text-text-color px-3 py-2 text-sm focus:outline-none focus:border-teacher-color focus:ring-1 focus:ring-teacher-color"
              />
            </label>

            <label className="block">
              <span className="block text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide mb-1.5">
                Usages max
              </span>
              <input
                type="number"
                min={1}
                max={1000}
                value={maxUses}
                onChange={(event) => setMaxUses(event.target.value)}
                className="w-full rounded-xl border border-border-color bg-secondary-color text-text-color px-3 py-2 text-sm focus:outline-none focus:border-teacher-color focus:ring-1 focus:ring-teacher-color"
              />
            </label>
          </div>

          <label className="block">
            <span className="block text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide mb-1.5">
              Groupe d'arrivée
            </span>
            <select
              value={groupId}
              onChange={(event) =>
                setGroupId(event.target.value === '' ? '' : Number(event.target.value))
              }
              className="w-full rounded-xl border border-border-color bg-secondary-color text-text-color px-3 py-2 text-sm focus:outline-none focus:border-teacher-color focus:ring-1 focus:ring-teacher-color"
            >
              <option value="">Aucun groupe</option>
              {groups.map((group) => (
                <option key={group.id} value={group.id}>
                  {group.name}
                </option>
              ))}
            </select>
          </label>

          <div className="flex justify-end gap-3">
            <Button variant="ghost" onClick={onClose} disabled={isSubmitting}>
              Fermer
            </Button>
            <Button
              variant="teacher"
              icon={Plus}
              iconSize={16}
              onClick={handleCreate}
              isLoading={isSubmitting}
            >
              Créer le lien
            </Button>
          </div>
        </div>
      </div>
    </Modal>
  );
}

function InvitationRow({
  invitation,
  copied,
  disabling,
  onCopy,
  onDisable,
}: {
  invitation: TeacherInvitation;
  copied: boolean;
  disabling: boolean;
  onCopy: () => void;
  onDisable: () => void;
}) {
  const progress = Math.min(100, (invitation.current_uses / invitation.max_uses) * 100);
  const title = invitation.label || invitation.group?.name || 'Lien sans groupe';
  const uses = `${invitation.current_uses}/${invitation.max_uses}`;

  return (
    <div
      role="button"
      tabIndex={0}
      onClick={onCopy}
      onKeyDown={(e) => e.key === 'Enter' && onCopy()}
      className="group relative overflow-hidden rounded-2xl border border-border-color bg-secondary-color p-4 cursor-pointer hover:border-teacher-color/30 transition-colors"
      title="Cliquer pour copier le lien"
    >
      <div
        className="absolute inset-y-0 left-0 bg-teacher-color/10"
        style={{ width: `${progress}%` }}
      />
      <div className="relative flex items-center gap-3">
        <div className="min-w-0 flex-1">
          <div className="flex items-center gap-2">
            <p className="truncate text-sm font-comfortaa-bold text-text-color">{title}</p>
            <span className="shrink-0 rounded-full bg-surface-color border border-border-color px-2 py-0.5 text-[10px] font-comfortaa-bold uppercase tracking-wide text-text-gray">
              {uses}
            </span>
          </div>
          <p className="mt-1 truncate text-xs text-text-gray font-mono">{inviteUrl(invitation)}</p>
          <p className="mt-1 text-xs text-text-gray">
            {invitation.group?.name
              ? `Groupe : ${invitation.group.name}`
              : 'Aucun groupe d’arrivée'}
          </p>
        </div>

        <button
          type="button"
          onClick={(e) => {
            e.stopPropagation();
            onCopy();
          }}
          className="grid h-9 w-9 place-items-center rounded-xl border border-border-color bg-surface-color text-text-gray hover:text-teacher-color transition-colors"
          title="Copier le lien"
        >
          {copied ? <Check size={16} className="text-success-color" /> : <Copy size={16} />}
        </button>
        <button
          type="button"
          onClick={(e) => {
            e.stopPropagation();
            onDisable();
          }}
          disabled={disabling}
          className="grid h-9 w-9 place-items-center rounded-xl border border-error-color/20 bg-error-color/5 text-error-color hover:bg-error-color/10 disabled:opacity-50 transition-colors"
          title="Désactiver le lien"
        >
          <Power size={16} />
        </button>
      </div>
    </div>
  );
}

function inviteUrl(invitation: TeacherInvitation): string {
  return `${window.location.origin}/join/${invitation.code}`;
}
