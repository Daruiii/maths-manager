import { useState } from 'react';
import { BookOpen } from 'lucide-react';
import { DSPreviewItem, DEFAULT_EXERCISE_MINUTES, PickableItem } from '@/types/models';
import { DS_DEFAULT_TITLE, DS_DEFAULT_LEVEL, DS_DEFAULT_INSTRUCTIONS } from '@/Constants/ds';
import LatexRenderer from '@/Components/Common/UI/LatexRenderer';
import EmptyState from '@/Components/Common/UI/EmptyState';
import KatexHtmlBlock from '@/Components/Common/UI/KatexHtmlBlock';
import EditableText from '@/Components/Common/UI/EditableText';

type EditingField = 'title' | 'level' | 'instructions' | null;

function formatTime(totalMinutes: number): string {
  if (totalMinutes === 0) return '0 min';
  const h = Math.floor(totalMinutes / 60);
  const m = totalMinutes % 60;
  if (h === 0) return `${m} min`;
  if (m === 0) return `${h}h`;
  return `${h}h${String(m).padStart(2, '0')}`;
}

function renderItemContent(item: PickableItem) {
  if (item.kind === 'problem') {
    if (item.statement) return <KatexHtmlBlock html={item.statement} />;
    if (item.latex_statement) {
      const images = item.image_paths ? Object.values(item.image_paths) : [];
      return <LatexRenderer latex={item.latex_statement} images={images} />;
    }
  }
  if ((item.kind === 'exercise' || item.kind === 'private') && item.latex_statement) {
    const images = item.image_paths ? Object.values(item.image_paths) : [];
    return <LatexRenderer latex={item.latex_statement} images={images} />;
  }
  return <p className="text-xs text-text-gray italic">Énoncé non disponible</p>;
}

interface Props {
  items: DSPreviewItem[];
  dsTitle: string;
  dsLevel: string;
  dsInstructions: string;
  onTitleChange: (v: string) => void;
  onLevelChange: (v: string) => void;
  onInstructionsChange: (v: string) => void;
}

export default function DSContent({
  items,
  dsTitle,
  dsLevel,
  dsInstructions,
  onTitleChange,
  onLevelChange,
  onInstructionsChange,
}: Props) {
  const [editingField, setEditingField] = useState<EditingField>(null);

  const totalMinutes = items.reduce((sum, i) => {
    if (i.item.kind === 'problem') return sum + (i.item.time ?? 0);
    return sum + DEFAULT_EXERCISE_MINUTES;
  }, 0);

  return (
    <div className="flex flex-col h-full overflow-hidden">
      {/* Subject Toolbar */}
      <div className="px-4 py-2 border-b border-border-color flex-shrink-0 flex items-center justify-between">
        <h2 className="text-sm font-comfortaa-bold text-text-color">
          Aperçu du DS
          {items.length > 0 && (
            <span className="ml-1.5 text-xs font-normal text-text-gray">
              {items.length} exercices
            </span>
          )}
        </h2>
        {totalMinutes > 0 && (
          <span className="text-xs text-text-gray">{formatTime(totalMinutes)}</span>
        )}
      </div>

      {/* Contenu */}
      <div className="flex-1 overflow-y-auto custom-scrollbar">
        {items.length === 0 ? (
          <div className="p-4">
            <EmptyState
              icon={BookOpen}
              description="Sélectionne des exercices pour voir l'aperçu du DS"
              accentColor="teacher"
            />
          </div>
        ) : (
          <div className="bg-surface-color p-6 space-y-6 min-h-full font-cmu-serif">
            {/* Header Académique (Corrigé - Tailles & Couleurs) */}
            <div className="text-center space-y-2 pb-6 border-b border-border-color">
              <div className="text-base font-bold uppercase text-text-color">
                <EditableText
                  value={dsTitle}
                  onChange={onTitleChange}
                  isEditing={editingField === 'title'}
                  onDoubleClick={() => setEditingField('title')}
                  onBlur={() => setEditingField(null)}
                  placeholder={DS_DEFAULT_TITLE}
                  className="text-center"
                  renderValue={(v: string) => (
                    <span>
                      {v.charAt(0)}
                      <span className="text-xs">{v.slice(1)}</span>
                    </span>
                  )}
                />
              </div>
              <div className="text-base font-bold uppercase text-text-color">
                <EditableText
                  value={dsLevel}
                  onChange={onLevelChange}
                  isEditing={editingField === 'level'}
                  onDoubleClick={() => setEditingField('level')}
                  onBlur={() => setEditingField(null)}
                  placeholder={DS_DEFAULT_LEVEL}
                  className="text-center"
                  renderValue={(v: string) => (
                    <span>
                      {v.charAt(0)}
                      <span className="text-xs">{v.slice(1)}</span>
                    </span>
                  )}
                />
              </div>
              <div className="text-sm font-cmu-italic text-text-color leading-relaxed pt-2">
                <EditableText
                  value={dsInstructions}
                  onChange={onInstructionsChange}
                  isEditing={editingField === 'instructions'}
                  onDoubleClick={() => setEditingField('instructions')}
                  onBlur={() => setEditingField(null)}
                  multiline
                  placeholder={DS_DEFAULT_INSTRUCTIONS}
                  className="text-center"
                />
              </div>
            </div>

            {/* Liste d'exercices */}
            <div className="space-y-12">
              {items.map((dsItem, index) => {
                const item = dsItem.item;
                return (
                  <div key={dsItem.uid} className="space-y-3">
                    <div className="flex items-baseline gap-2">
                      <span className="font-bold text-sm flex-shrink-0">Exercice {index + 1}.</span>
                    </div>
                    <div className="text-sm leading-relaxed">{renderItemContent(item)}</div>
                  </div>
                );
              })}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
