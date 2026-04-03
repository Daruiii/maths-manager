import { ReactNode, useMemo, useState } from 'react';
import { Decoration, EditorView } from '@codemirror/view';
import { Extension } from '@codemirror/state';
import { PrivateExerciseFormData, LatexField } from '@/types/models';
import { LATEX_TABS, LATEX_EDITOR_EXTENSIONS } from '@/Constants/latexEditor';
import { useLatexEditorInteractions } from '@/Hooks/Content/useLatexEditorInteractions';
import { findMissingGraphReferences } from '@/Utils/latexInsertion';
import LatexPanelHeader from '@/Components/Common/Form/LatexPanelHeader';
import LatexPanelEditorArea from '@/Components/Common/Form/LatexPanelEditorArea';

// ─── Props ────────────────────────────────────────────────────────────────────

interface Props {
  data: PrivateExerciseFormData;
  set: <K extends keyof PrivateExerciseFormData>(key: K, value: PrivateExerciseFormData[K]) => void;
  errors: Partial<Record<keyof PrivateExerciseFormData, string>>;
  setFocusedField: (field: LatexField) => void;
  images?: Record<string, string>;
  imageSlot?: ReactNode;
}

// ─── Component ────────────────────────────────────────────────────────────────

export default function LatexPanel({
  data,
  set,
  errors,
  setFocusedField,
  images = {},
  imageSlot,
}: Props) {
  const [activeTab, setActiveTab] = useState<LatexField>('latex_statement');
  const [mode, setMode] = useState<'latex' | 'preview'>('latex');
  const [isAiPanelOpen, setIsAiPanelOpen] = useState(false);
  const [isEditorFocused, setIsEditorFocused] = useState(false);
  const [blurredTabs, setBlurredTabs] = useState<Record<LatexField, boolean>>({
    latex_statement: false,
    latex_solution: false,
    latex_clue: false,
  });
  const {
    editorViewRef,
    draggingFile,
    insertSnippet,
    handleDragOver,
    handleDragLeave,
    handleDrop,
  } = useLatexEditorInteractions({
    activeTab,
    value: data[activeTab],
    mode,
    isAiPanelOpen,
    set,
    setFocusedField,
  });

  const tab = LATEX_TABS.find((t) => t.key === activeTab)!;
  const value = data[activeTab];
  const error = errors[activeTab];
  const shouldShowMissingGraphHint = blurredTabs[activeTab] && !isEditorFocused;
  const missingGraphDecorations = useMemo(() => {
    if (!shouldShowMissingGraphHint) {
      return Decoration.none;
    }

    const ranges = findMissingGraphReferences(value, images).map(({ id, idStart, idEnd }) =>
      Decoration.mark({
        class: 'cm-missing-graph-ref',
        attributes: {
          title: `Image introuvable pour \\graph{${id}}. Vérifiez le nom ou ajoutez cette image.`,
          'aria-label': `Image introuvable pour graph ${id}`,
        },
      }).range(idStart, idEnd)
    );

    return ranges.length > 0 ? Decoration.set(ranges, true) : Decoration.none;
  }, [images, shouldShowMissingGraphHint, value]);
  const latexEditorExtensions: Extension[] = useMemo(() => {
    return [...LATEX_EDITOR_EXTENSIONS, EditorView.decorations.of(missingGraphDecorations)];
  }, [missingGraphDecorations]);

  function switchTab(key: LatexField) {
    setActiveTab(key);
    setFocusedField(key);
  }

  return (
    <div className="flex h-full flex-col overflow-hidden rounded-2xl border border-border-color bg-surface-color">
      <LatexPanelHeader
        activeTab={activeTab}
        setActiveTab={switchTab}
        errors={errors}
        mode={mode}
        setMode={setMode}
        isAiPanelOpen={isAiPanelOpen}
        setIsAiPanelOpen={setIsAiPanelOpen}
      />

      <LatexPanelEditorArea
        isAiPanelOpen={isAiPanelOpen}
        mode={mode}
        value={value}
        tab={tab}
        error={error}
        images={images}
        activeTab={activeTab}
        setFocusedField={setFocusedField}
        setActiveTabValue={(nextValue) => set(activeTab, nextValue)}
        setIsEditorFocused={setIsEditorFocused}
        markTabAsBlurred={(field) =>
          setBlurredTabs((prev) => ({
            ...prev,
            [field]: true,
          }))
        }
        editorViewRef={editorViewRef}
        editorExtensions={latexEditorExtensions}
        insertSnippet={insertSnippet}
        draggingFile={draggingFile}
        onDragOver={handleDragOver}
        onDragLeave={handleDragLeave}
        onDrop={handleDrop}
        imageSlot={imageSlot}
      />
    </div>
  );
}
