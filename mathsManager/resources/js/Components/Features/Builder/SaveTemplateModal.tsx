import { useState, useEffect } from 'react';
import { router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { Save } from 'lucide-react';
import { StudentGroup, BuilderType, TemplatePayload, LoadedTemplate } from '@/types/models';
import SlidePanel from '@/Components/Common/UI/SlidePanel';
import Button from '@/Components/Common/UI/Button';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  type: BuilderType;
  groups: StudentGroup[];
  /** Payload courant du builder — absent en mode rename-only depuis le Bureau */
  payload?: TemplatePayload;
  /** Template chargé depuis le Bureau — active le mode update */
  editingTemplate?: LoadedTemplate | { id: number; name: string; student_group_id: number | null };
}

export default function SaveTemplateModal({
  isOpen,
  onClose,
  type,
  groups,
  payload,
  editingTemplate,
}: Props) {
  const isFromTemplate = !!editingTemplate;
  const isRenameOnly = isFromTemplate && !payload;

  const [name, setName] = useState(editingTemplate?.name ?? '');
  const [selectedGroupId, setSelectedGroupId] = useState<number | ''>(
    editingTemplate?.student_group_id ?? ''
  );
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    setName(editingTemplate?.name ?? '');
    setSelectedGroupId(editingTemplate?.student_group_id ?? '');
  }, [editingTemplate]);

  function handleClose() {
    setName(editingTemplate?.name ?? '');
    setSelectedGroupId(editingTemplate?.student_group_id ?? '');
    onClose();
  }

  function handleSubmit() {
    if (!name.trim()) return;
    if (!isRenameOnly && !payload?.items.length) return;

    setIsSubmitting(true);

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const p = payload as any;

    if (isFromTemplate) {
      router.patch(
        route('teacher.templates.update', { template: editingTemplate!.id }),
        {
          name: name.trim(),
          student_group_id: selectedGroupId || null,
          ...(payload ? { payload: p } : {}),
        },
        {
          preserveState: true,
          preserveScroll: true,
          onSuccess: () => handleClose(),
          onFinish: () => setIsSubmitting(false),
        }
      );
    } else {
      router.post(
        route('teacher.templates.store'),
        { type, name: name.trim(), student_group_id: selectedGroupId || null, payload: p },
        {
          preserveState: true,
          preserveScroll: true,
          onSuccess: () => handleClose(),
          onFinish: () => setIsSubmitting(false),
        }
      );
    }
  }

  const canSubmit = !!name.trim() && (isRenameOnly || !!payload?.items.length);

  const submitLabel = isSubmitting
    ? 'Sauvegarde…'
    : isRenameOnly
      ? 'Renommer'
      : isFromTemplate
        ? 'Mettre à jour'
        : 'Sauvegarder';

  return (
    <SlidePanel
      isOpen={isOpen}
      onClose={handleClose}
      title={
        isRenameOnly
          ? 'Renommer le modèle'
          : isFromTemplate
            ? 'Mettre à jour le modèle'
            : 'Sauvegarder le modèle'
      }
      subtitle={
        isRenameOnly
          ? 'Modifiez le nom ou le groupe associé.'
          : isFromTemplate
            ? 'Modifiez le nom ou le groupe, puis sauvegardez.'
            : 'Donnez un nom à ce modèle pour le retrouver dans Mon Bureau.'
      }
      size="sm"
      footer={
        <div className="flex gap-2 justify-end">
          <Button variant="ghost" size="sm" onClick={handleClose} disabled={isSubmitting}>
            Annuler
          </Button>
          <Button
            variant="primary"
            size="sm"
            icon={Save}
            onClick={handleSubmit}
            disabled={!canSubmit || isSubmitting}
          >
            {submitLabel}
          </Button>
        </div>
      }
    >
      <form
        onSubmit={(e) => {
          e.preventDefault();
          handleSubmit();
        }}
        className="space-y-4 p-4"
      >
        <div>
          <label className="block text-xs font-comfortaa-bold text-text-color mb-1">
            Nom du modèle <span className="text-error-color">*</span>
          </label>
          <input
            type="text"
            value={name}
            onChange={(e) => setName(e.target.value)}
            placeholder="ex. DS Trigo 3ème"
            maxLength={255}
            autoFocus
            className="w-full px-3 py-2 text-sm rounded-lg border border-border-color bg-surface-color text-text-color placeholder:text-text-gray focus:outline-none focus:ring-2 focus:ring-teacher-color/30 focus:border-teacher-color"
          />
        </div>

        {groups.length > 0 && (
          <div>
            <label className="block text-xs font-comfortaa-bold text-text-color mb-1">
              Groupe associé <span className="text-text-gray font-normal">(optionnel)</span>
            </label>
            <select
              value={selectedGroupId}
              onChange={(e) => setSelectedGroupId(e.target.value ? Number(e.target.value) : '')}
              className="w-full px-3 py-2 text-sm rounded-lg border border-border-color bg-surface-color text-text-color focus:outline-none focus:ring-2 focus:ring-teacher-color/30 focus:border-teacher-color"
            >
              <option value="">Aucun (modèle générique)</option>
              {groups.map((g) => (
                <option key={g.id} value={g.id}>
                  {g.name}
                </option>
              ))}
            </select>
          </div>
        )}
      </form>
    </SlidePanel>
  );
}
