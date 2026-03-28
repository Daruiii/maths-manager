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
    .replace(/\\\\[ \t]*/g, '<br>');
}

// ─── Types ────────────────────────────────────────────────────────────────────

interface Props {
  html: string;
  className?: string;
}

// ─── Component ───────────────────────────────────────────────────────────────

/**
 * Rend du HTML pré-converti côté serveur avec un passage KaTeX pour les maths.
 *
 * Même pattern que LatexRenderer : innerHTML dans useEffect pour éviter que React
 * n'écrase le DOM que KaTeX vient de construire entre les re-renders.
 */
export default function KatexHtmlBlock({ html, className = '' }: Props) {
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
