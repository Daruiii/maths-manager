import { useRef, useState } from 'react';
import { EditorView } from '@codemirror/view';
import { LatexField, PrivateExerciseFormData } from '@/types/models';
import { buildContextualSnippetInsertion, buildGraphSnippet } from '@/Utils/latexInsertion';

interface Params {
  activeTab: LatexField;
  value: string;
  mode: 'latex' | 'preview';
  isAiPanelOpen: boolean;
  set: <K extends keyof PrivateExerciseFormData>(key: K, value: PrivateExerciseFormData[K]) => void;
  setFocusedField: (field: LatexField) => void;
}

export function useLatexEditorInteractions({
  activeTab,
  value,
  mode,
  isAiPanelOpen,
  set,
  setFocusedField,
}: Params) {
  const editorViewRef = useRef<EditorView | null>(null);
  const [draggingFile, setDraggingFile] = useState(false);

  function insertSnippet(snippet: string, atPos?: number) {
    const view = editorViewRef.current;

    if (view) {
      const insertAt = atPos ?? view.state.selection.main.from;
      const docText = view.state.doc.toString();
      const { insert, cursorAnchor } = buildContextualSnippetInsertion(docText, insertAt, snippet);

      view.dispatch({
        changes: { from: insertAt, to: insertAt, insert },
        selection: { anchor: cursorAnchor },
        scrollIntoView: true,
      });

      set(activeTab, view.state.doc.toString());
      setFocusedField(activeTab);
      return;
    }

    const fallbackFrom = value.length;
    const { insert } = buildContextualSnippetInsertion(value, fallbackFrom, snippet);
    const nextValue = `${value}${insert}`;
    set(activeTab, nextValue);
    setFocusedField(activeTab);
  }

  function handleDragOver(e: React.DragEvent) {
    if (mode !== 'latex' || isAiPanelOpen) return;
    const hasLatexImageName = e.dataTransfer.types.includes('application/x-maths-latex-image-name');
    if (!hasLatexImageName) return;
    e.preventDefault();
    setDraggingFile(true);
  }

  function handleDragLeave(e: React.DragEvent) {
    if (!e.currentTarget.contains(e.relatedTarget as Node)) setDraggingFile(false);
  }

  function handleDrop(e: React.DragEvent) {
    if (mode !== 'latex' || isAiPanelOpen) return;
    e.preventDefault();
    setDraggingFile(false);

    const draggedImageName = e.dataTransfer.getData('application/x-maths-latex-image-name');
    if (!draggedImageName) return;

    const insertAt = editorViewRef.current?.posAtCoords({ x: e.clientX, y: e.clientY });
    insertSnippet(buildGraphSnippet(draggedImageName), insertAt ?? undefined);
  }

  return {
    editorViewRef,
    draggingFile,
    insertSnippet,
    handleDragOver,
    handleDragLeave,
    handleDrop,
  };
}
