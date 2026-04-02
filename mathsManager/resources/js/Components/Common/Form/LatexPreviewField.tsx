import { useState } from 'react';
import { Eye, EyeOff } from 'lucide-react';
import LatexRenderer from '@/Components/Common/UI/LatexRenderer';

interface Props {
  label: string;
  value: string;
  onChange: (v: string) => void;
  onFocus?: () => void;
  placeholder?: string;
  /** Map nom → URL (blob: ou /storage/...) pour le rendu LaTeX */
  images?: Record<string, string>;
  error?: string;
  rows?: number;
}

/**
 * Champ LaTeX avec aperçu KaTeX intégré (toggle).
 * Utilisé dans Mon Bureau et potentiellement le builder TD.
 */
export default function LatexPreviewField({
  label,
  value,
  onChange,
  onFocus,
  placeholder,
  images = {},
  error,
  rows = 5,
}: Props) {
  const [showPreview, setShowPreview] = useState(false);

  return (
    <div className="space-y-1.5">
      <div className="flex items-center justify-between">
        <label className="text-xs font-comfortaa-bold text-text-color">{label}</label>
        {value.trim() && (
          <button
            type="button"
            onClick={() => setShowPreview((p) => !p)}
            className="flex items-center gap-1 text-xxs text-text-gray hover:text-teacher-color transition-colors"
          >
            {showPreview ? (
              <>
                <EyeOff size={12} /> Masquer
              </>
            ) : (
              <>
                <Eye size={12} /> Aperçu
              </>
            )}
          </button>
        )}
      </div>

      <textarea
        value={value}
        onChange={(e) => onChange(e.target.value)}
        onFocus={onFocus}
        placeholder={placeholder}
        rows={rows}
        className={[
          'w-full px-3 py-2 text-sm font-mono bg-surface-color border rounded-xl resize-y',
          'text-text-color placeholder:text-text-gray/50 outline-none transition-colors',
          'focus:border-teacher-color custom-scrollbar',
          error ? 'border-error-color' : 'border-border-color',
        ].join(' ')}
      />

      {error && <p className="text-xxs text-error-color">{error}</p>}

      {showPreview && value.trim() && (
        <div className="p-3 bg-surface-color border border-border-color rounded-xl text-sm">
          <LatexRenderer latex={value} images={images} />
        </div>
      )}
    </div>
  );
}
