import { Code2, Eye, ImagePlus, Sparkles } from 'lucide-react';
import { LatexField, PrivateExerciseFormData } from '@/types/models';
import { LATEX_TABS } from '@/Constants/latexEditor';

interface Props {
  activeTab: LatexField;
  setActiveTab: (field: LatexField) => void;
  errors: Partial<Record<keyof PrivateExerciseFormData, string>>;
  mode: 'latex' | 'preview';
  setMode: (mode: 'latex' | 'preview') => void;
  isAiPanelOpen: boolean;
  setIsAiPanelOpen: (value: boolean) => void;
}

export default function LatexPanelHeader({
  activeTab,
  setActiveTab,
  errors,
  mode,
  setMode,
  isAiPanelOpen,
  setIsAiPanelOpen,
}: Props) {
  return (
    <div className="shrink-0 flex flex-col gap-2 border-b border-border-color px-3 py-2 sm:flex-row sm:items-center sm:justify-between">
      <div className="flex gap-1">
        {LATEX_TABS.map((t) => (
          <button
            key={t.key}
            type="button"
            onClick={() => setActiveTab(t.key)}
            className={[
              'px-3 py-1.5 text-xs font-comfortaa-bold transition-colors border-b-2 -mb-px',
              activeTab === t.key
                ? 'text-teacher-color border-teacher-color'
                : 'text-text-gray border-transparent hover:text-text-color',
            ].join(' ')}
          >
            {t.label}
            {t.required && !errors[t.key] && <span className="ml-0.5 text-error-color">*</span>}
            {errors[t.key] && (
              <span className="ml-1 w-1.5 h-1.5 rounded-full bg-error-color inline-block" />
            )}
          </button>
        ))}
      </div>

      <div className="flex items-center justify-center gap-2 self-center sm:self-auto">
        <button
          type="button"
          onClick={() => setIsAiPanelOpen(!isAiPanelOpen)}
          className={[
            'relative inline-flex h-8 w-8 items-center justify-center rounded-full border border-border-color transition-colors',
            isAiPanelOpen
              ? 'bg-teacher-color/15 text-teacher-color'
              : 'text-text-gray hover:border-teacher-color/60 hover:text-text-color',
          ].join(' ')}
          title="Bientôt : Panel IA PNG/PDF vers LaTeX"
          aria-label="Ouvrir le panel IA PNG/PDF vers LaTeX"
        >
          <ImagePlus size={14} />
          <span className="absolute -right-1 -top-1 inline-flex h-3.5 w-3.5 items-center justify-center rounded-full bg-teacher-color text-white">
            <Sparkles size={9} />
          </span>
        </button>

        <div className="flex items-center gap-0.5 rounded-xl border-2 border-border-color p-0.5">
          {(
            [
              { key: 'latex', icon: <Code2 size={11} />, label: 'LaTeX' },
              { key: 'preview', icon: <Eye size={11} />, label: 'Aperçu' },
            ] as const
          ).map(({ key, icon, label }) => (
            <button
              key={key}
              type="button"
              onClick={() => {
                setIsAiPanelOpen(false);
                setMode(key);
              }}
              className={[
                'flex items-center gap-1.5 px-3 py-1 text-xxs rounded-lg transition-all duration-150 font-comfortaa',
                mode === key && !isAiPanelOpen
                  ? 'bg-teacher-color/15 text-teacher-color font-comfortaa-bold'
                  : 'text-text-gray hover:text-text-color',
              ].join(' ')}
            >
              {icon} {label}
            </button>
          ))}
        </div>
      </div>
    </div>
  );
}
