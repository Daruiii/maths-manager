import { useState } from 'react';
import { BookOpen } from 'lucide-react';
import { usePage } from '@inertiajs/react';
import { DSPreviewItem, PickableItem } from '@/types/models';
import { TD_DEFAULT_TITLE, TD_DEFAULT_LEVEL, TD_DEFAULT_INSTRUCTIONS } from '@/Constants/td';
import { getMacrosForContent } from '@/Utils/MacroRegistry';
import { PageProps } from '@/types';
import LatexRenderer from '@/Components/Common/UI/LatexRenderer';
import EmptyState from '@/Components/Common/UI/EmptyState';
import EditableText from '@/Components/Common/UI/EditableText';

type EditingField = 'title' | 'level' | 'instructions' | null;

function renderItemContent(item: PickableItem, teacherMacros: Record<string, string>) {
  if ((item.kind === 'exercise' || item.kind === 'private') && item.latex_statement) {
    const images = item.image_paths
      ? Object.fromEntries(Object.entries(item.image_paths).map(([k, v]) => [k, `/storage/${v}`]))
      : {};
    const macros = item.kind === 'private' ? teacherMacros : undefined;
    return <LatexRenderer latex={item.latex_statement} images={images} macros={macros} />;
  }
  return <p className="text-xs text-text-gray italic">Énoncé non disponible</p>;
}

interface Props {
  items: DSPreviewItem[];
  tdTitle: string;
  tdLevel: string;
  tdInstructions: string;
  onTitleChange: (v: string) => void;
  onLevelChange: (v: string) => void;
  onInstructionsChange: (v: string) => void;
}

export default function TDContent({
  items,
  tdTitle,
  tdLevel,
  tdInstructions,
  onTitleChange,
  onLevelChange,
  onInstructionsChange,
}: Props) {
  const { auth } = usePage<PageProps>().props;
  const teacherMacros = getMacrosForContent('private-content', auth.user?.latex_macros);
  const [editingField, setEditingField] = useState<EditingField>(null);

  return (
    <div className="flex flex-col h-full overflow-hidden">
      <div className="px-4 py-2 border-b border-border-color flex-shrink-0 flex items-center justify-between">
        <h2 className="text-sm font-comfortaa-bold text-text-color">
          Aperçu du TD
          {items.length > 0 && (
            <span className="ml-1.5 text-xs font-normal text-text-gray">
              {items.length} exercices
            </span>
          )}
        </h2>
      </div>

      <div className="flex-1 overflow-y-auto custom-scrollbar">
        {items.length === 0 ? (
          <div className="p-4">
            <EmptyState
              icon={BookOpen}
              description="Sélectionne des exercices pour voir l'aperçu du TD"
              accentColor="teacher"
            />
          </div>
        ) : (
          <div className="bg-surface-color p-6 space-y-6 min-h-full font-cmu-serif">
            <div className="text-center space-y-2 pb-6 border-b border-border-color">
              <div className="text-base font-bold uppercase text-text-color">
                <EditableText
                  value={tdTitle}
                  onChange={onTitleChange}
                  isEditing={editingField === 'title'}
                  onDoubleClick={() => setEditingField('title')}
                  onBlur={() => setEditingField(null)}
                  placeholder={TD_DEFAULT_TITLE}
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
                  value={tdLevel}
                  onChange={onLevelChange}
                  isEditing={editingField === 'level'}
                  onDoubleClick={() => setEditingField('level')}
                  onBlur={() => setEditingField(null)}
                  placeholder={TD_DEFAULT_LEVEL}
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
                  value={tdInstructions}
                  onChange={onInstructionsChange}
                  isEditing={editingField === 'instructions'}
                  onDoubleClick={() => setEditingField('instructions')}
                  onBlur={() => setEditingField(null)}
                  multiline
                  placeholder={TD_DEFAULT_INSTRUCTIONS}
                  className="text-center"
                />
              </div>
            </div>

            <div className="space-y-12">
              {items.map((tdItem, index) => {
                const item = tdItem.item;
                return (
                  <div key={tdItem.uid} className="space-y-3">
                    <div className="flex items-baseline gap-2">
                      <span className="font-bold text-sm flex-shrink-0">Exercice {index + 1}.</span>
                    </div>
                    <div className="text-sm leading-relaxed">
                      {renderItemContent(item, teacherMacros)}
                    </div>
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
