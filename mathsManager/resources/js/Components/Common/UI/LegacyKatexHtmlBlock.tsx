import { useEffect, useRef } from 'react';
import renderMathInElement from 'katex/contrib/auto-render';
import { KATEX_DELIMITERS, KATEX_MACROS } from '@/Utils/katex';

// ─── Helpers ─────────────────────────────────────────────────────────────────

/**
 * Corrige les anciens artefacts du HTML pré-converti côté serveur :
 * - URLs de stockage migrées : ds_exercises/ds_exercise_N/N.ext → ds-exercises/ds-exercise-N/img-N.ext
 * - Séparateurs LaTeX résiduels : \\ → <br>
 */
function normalizeServerHtml(html: string): string {
  return html
    .replace(
      /storage\/ds_exercises\/ds_exercise_(\d+)\/(\d+)\.(png|jpg|jpeg|gif|webp)/g,
      'storage/ds-exercises/ds-exercise-$1/img-$2.$3'
    )
    .replace(/(["'(])storage\//g, '$1/storage/')
    .replace(/(["'(])public\/storage\//g, '$1/storage/')
    .replace(/\\\\[ \t]*/g, '<br>');
}

// ─── Types ────────────────────────────────────────────────────────────────────

interface Props {
  html: string;
  className?: string;
}

// ─── Component ───────────────────────────────────────────────────────────────

/**
 * Rend du HTML pré-converti côté serveur (legacy) avec un passage KaTeX pour les maths.
 * Utilisé pour les Exercise et Problem existants dont le HTML est stocké en base.
 *
 * Pour les nouveaux contenus (PrivateExercise et futurs), utiliser LatexRenderer.
 */
export default function LegacyKatexHtmlBlock({ html, className = '' }: Props) {
  const ref = useRef<HTMLDivElement>(null);
  const normalizedHtml = normalizeServerHtml(html);

  useEffect(() => {
    if (!ref.current) return;
    ref.current.innerHTML = normalizedHtml;
    renderMathInElement(ref.current, {
      delimiters: KATEX_DELIMITERS,
      macros: KATEX_MACROS,
      throwOnError: false,
    });
  }, [normalizedHtml]);

  return <div ref={ref} className={`latex-content ${className}`} />;
}
