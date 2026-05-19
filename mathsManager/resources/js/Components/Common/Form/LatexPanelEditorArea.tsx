import { EditorView } from '@codemirror/view';
import CodeMirror from '@uiw/react-codemirror';
import { ReactNode } from 'react';
import { Extension } from '@codemirror/state';
import { Sigma } from 'lucide-react';
import { LatexField } from '@/types/models';
import { LatexTabConfig, LATEX_SNIPPETS } from '@/Constants/latexEditor';
import AiPanel from '@/Components/Common/Form/AiPanel';
import InputError from '@/Components/Common/Form/InputError';
import LatexRenderer from '@/Components/Common/UI/LatexRenderer';

interface Props {
  isAiPanelOpen: boolean;
  mode: 'latex' | 'preview';
  value: string;
  tab: LatexTabConfig;
  error?: string;
  images: Record<string, string>;
  macros: Record<string, string>;
  activeTab: LatexField;
  setFocusedField: (field: LatexField) => void;
  setActiveTabValue: (value: string) => void;
  setIsEditorFocused: (value: boolean) => void;
  markTabAsBlurred: (field: LatexField) => void;
  editorViewRef: React.MutableRefObject<EditorView | null>;
  editorExtensions: Extension[];
  insertSnippet: (snippet: string) => void;
  draggingFile: boolean;
  onDragOver: (e: React.DragEvent) => void;
  onDragLeave: (e: React.DragEvent) => void;
  onDrop: (e: React.DragEvent) => void;
  imageSlot?: ReactNode;
  onManageMacros?: () => void;
}

export default function LatexPanelEditorArea({
  isAiPanelOpen,
  mode,
  value,
  tab,
  error,
  images,
  macros,
  activeTab,
  setFocusedField,
  setActiveTabValue,
  setIsEditorFocused,
  markTabAsBlurred,
  editorViewRef,
  editorExtensions,
  insertSnippet,
  draggingFile,
  onDragOver,
  onDragLeave,
  onDrop,
  imageSlot,
  onManageMacros,
}: Props) {
  return (
    <>
      <div
        className="relative flex-1 min-h-0 p-3"
        onDragOver={onDragOver}
        onDragLeave={onDragLeave}
        onDrop={onDrop}
      >
        {isAiPanelOpen ? (
          <AiPanel />
        ) : (
          <>
            <div
              className={`mb-2 flex items-center justify-between gap-1.5 ${mode === 'latex' ? 'flex' : 'hidden'}`}
            >
              <div className="flex flex-wrap items-center gap-1.5">
                {LATEX_SNIPPETS.map((snippet) => (
                  <button
                    key={snippet.label}
                    type="button"
                    onClick={() => insertSnippet(snippet.value)}
                    className="rounded-lg border border-border-color px-2 py-1 text-xxs text-text-gray transition-colors hover:border-teacher-color/50 hover:text-text-color"
                  >
                    {snippet.label}
                  </button>
                ))}
              </div>
              {onManageMacros && (
                <button
                  type="button"
                  onClick={onManageMacros}
                  className="shrink-0 flex items-center gap-1 px-2.5 py-1 text-xxs font-comfortaa-bold rounded-lg border border-border-color text-text-gray transition-colors hover:border-teacher-color/60 hover:text-teacher-color"
                  title="Gérer mes macros LaTeX"
                >
                  <Sigma size={11} />
                  Macros
                </button>
              )}
            </div>

            <div
              className={`overflow-hidden rounded-2xl border-2 border-border-color bg-surface-color shadow-sm transition-all duration-200 ${
                mode === 'latex' ? 'h-[calc(100%-34px)]' : 'hidden h-0'
              }`}
            >
              <CodeMirror
                key={activeTab}
                value={value}
                height="100%"
                basicSetup={{
                  lineNumbers: true,
                  foldGutter: false,
                  highlightActiveLineGutter: false,
                }}
                extensions={editorExtensions}
                placeholder={tab.placeholder}
                onCreateEditor={(view) => {
                  editorViewRef.current = view;
                }}
                onFocus={() => {
                  setFocusedField(activeTab);
                  setIsEditorFocused(true);
                }}
                onBlur={() => {
                  setIsEditorFocused(false);
                  markTabAsBlurred(activeTab);
                }}
                onChange={setActiveTabValue}
                className="h-full text-sm"
              />
            </div>

            <div
              className={`${mode === 'preview' ? 'h-full' : 'hidden h-0'} overflow-y-auto custom-scrollbar p-2 text-sm font-cmu-serif`}
            >
              {value.trim() ? (
                <LatexRenderer latex={value} images={images} macros={macros} />
              ) : (
                <p className="text-text-gray/50 italic text-xs">{tab.label} vide.</p>
              )}
            </div>

            <div className={mode === 'latex' ? 'block' : 'hidden'}>
              <InputError message={error} />
            </div>
          </>
        )}

        {draggingFile && (
          <div className="absolute inset-0 z-10 m-1 rounded-xl border-2 border-dashed border-teacher-color bg-teacher-color/10 flex items-center justify-center pointer-events-none">
            <p className="text-sm font-comfortaa-bold text-teacher-color">
              Déposer l'image pour insertion LaTeX
            </p>
          </div>
        )}
      </div>

      {imageSlot && <div className="shrink-0 border-t border-border-color">{imageSlot}</div>}
    </>
  );
}
