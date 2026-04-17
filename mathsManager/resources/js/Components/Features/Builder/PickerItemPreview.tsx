import { X, Clock, Calendar, MapPin } from 'lucide-react';
import {
  PickableItem,
  PickableProblem,
  PickableExercise,
  PickablePrivateExercise,
} from '@/types/models';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import LatexRenderer from '@/Components/Common/UI/LatexRenderer';
import { getMacrosForContent } from '@/Utils/MacroRegistry';
import LegacyKatexHtmlBlock from '@/Components/Common/UI/LegacyKatexHtmlBlock';
import { getRenderablePickableContent } from '@/Utils/pickableItemContent';

interface Props {
  item: PickableItem;
  onClose: () => void;
}

function DifficultyDots({ value }: { value: number | null }) {
  if (!value) return null;
  return (
    <span className="inline-flex items-center gap-0.5 px-2 py-1 rounded-lg bg-surface-color">
      {Array.from({ length: 5 }).map((_, i) => (
        <span
          key={i}
          className={`w-2 h-2 rounded-full transition-colors ${i < value ? 'bg-teacher-color' : 'bg-border-color'}`}
        />
      ))}
    </span>
  );
}

function MetaBadge({ icon: Icon, label }: { icon?: React.ElementType; label: string }) {
  return (
    <span className="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-surface-color text-xs text-text-gray">
      {Icon && <Icon size={11} />}
      {label}
    </span>
  );
}

function Breadcrumb({ item }: { item: PickableItem }) {
  if (item.kind === 'problem') {
    const p = item as PickableProblem;
    const title = p.multiple_chapter?.title;
    if (!title) return null;
    return (
      <p className="text-xs text-text-gray leading-snug truncate" title={title}>
        {title}
      </p>
    );
  }
  if (item.kind === 'exercise') {
    const ex = item as PickableExercise;
    const chapter = ex.subchapter?.chapter?.title;
    const sub = ex.subchapter?.title;
    const label = chapter && sub ? `${chapter} · ${sub}` : (sub ?? chapter ?? null);
    if (!label) return null;
    return (
      <p className="text-xs text-text-gray leading-snug truncate" title={label}>
        {label}
      </p>
    );
  }
  return (
    <span className="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-teacher-color/10 text-teacher-color text-xs font-medium w-fit">
      Privé
    </span>
  );
}

export default function PickerItemPreview({ item, onClose }: Props) {
  const { auth } = usePage<PageProps>().props;
  const difficulty =
    item.kind === 'problem'
      ? (item as PickableProblem).difficulty
      : item.kind === 'exercise'
        ? (item as PickableExercise).difficulty
        : (item as PickablePrivateExercise).difficulty;

  const time =
    item.kind === 'problem'
      ? (item as PickableProblem).time > 0
        ? (item as PickableProblem).time
        : null
      : item.kind === 'private'
        ? ((item as PickablePrivateExercise).time ?? null)
        : null;

  const year = item.kind === 'problem' ? (item as PickableProblem).year : null;
  const academy = item.kind === 'problem' ? (item as PickableProblem).academy : null;
  const harder = item.kind === 'problem' ? (item as PickableProblem).harder_exercise : false;

  const { statementHtml, latexStatement, images } = getRenderablePickableContent(item);

  const macros =
    item.kind === 'private'
      ? getMacrosForContent('private-content', auth.user?.latex_macros)
      : getMacrosForContent('global-content');

  const hasMeta = difficulty != null || (time != null && time > 0) || year || academy || harder;

  return (
    <div className="bg-secondary-color/95 backdrop-blur-sm border border-teacher-color/20 rounded-xl shadow-2xl shadow-black/20 flex flex-col overflow-hidden h-full min-h-0">
      {/* Header sticky */}
      <div className="flex items-start justify-between gap-2 px-4 pt-4 pb-3 border-b border-border-color/80 bg-surface-color/40">
        <div className="flex flex-col gap-1.5 min-w-0">
          <p className="text-sm font-comfortaa-bold text-text-color leading-snug">{item.name}</p>
          <Breadcrumb item={item} />
        </div>
        <button
          type="button"
          onClick={onClose}
          aria-label="Fermer"
          className="shrink-0 flex items-center justify-center w-7 h-7 rounded-lg text-text-gray hover:text-text-color hover:bg-surface-color transition-colors mt-0.5"
        >
          <X size={14} />
        </button>
      </div>

      {/* Scrollable body */}
      <div className="flex-1 min-h-0 overflow-y-auto custom-scrollbar flex flex-col gap-3 px-4 py-3">
        {/* Meta badges */}
        {hasMeta && (
          <div className="flex items-center flex-wrap gap-1.5">
            {difficulty != null && <DifficultyDots value={difficulty} />}
            {time != null && time > 0 && <MetaBadge icon={Clock} label={`${time} min`} />}
            {year && <MetaBadge icon={Calendar} label={String(year)} />}
            {academy && <MetaBadge icon={MapPin} label={academy} />}
            {harder && (
              <span className="inline-flex items-center px-2 py-1 rounded-lg bg-error-color/10 text-error-color text-xs font-medium">
                ★ Difficile
              </span>
            )}
          </div>
        )}

        {/* Statement */}
        {statementHtml && (
          <div className="border-t border-border-color pt-3">
            <LegacyKatexHtmlBlock html={statementHtml} />
          </div>
        )}

        {!statementHtml && latexStatement && (
          <div className="border-t border-border-color pt-3">
            <LatexRenderer
              latex={latexStatement}
              images={images}
              macros={macros}
              className="text-sm text-text-color"
            />
          </div>
        )}

        {!statementHtml && !latexStatement && (
          <p className="text-xs text-text-gray italic">Aucun énoncé disponible.</p>
        )}
      </div>
    </div>
  );
}
