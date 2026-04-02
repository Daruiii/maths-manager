import { useEffect, useRef } from 'react';
import renderMathInElement from 'katex/contrib/auto-render';
import 'katex/dist/katex.min.css';
import { KATEX_DELIMITERS, KATEX_MACROS } from '@/Utils/katex';
import { convertLatexToHtml } from '@/Utils/latexConverter';

interface Props {
  latex: string;
  /** Map nom → URL complète (blob: pour pending, /storage/... pour sauvegardé) */
  images?: Record<string, string>;
  className?: string;
}

/**
 * Rend un contenu LaTeX en HTML avec KaTeX.
 * Pipeline : convertLatexToHtml → innerHTML (via useEffect) → renderMathInElement (KaTeX).
 *
 * On évite dangerouslySetInnerHTML : React le re-applique à chaque render même si html
 * n'a pas changé, effaçant le DOM que KaTeX vient de construire.
 * En gérant innerHTML dans useEffect (déclenché uniquement quand html change), le DOM
 * KaTeX persiste entre les re-renders du parent.
 */
export default function LatexRenderer({ latex, images = {}, className = '' }: Props) {
  const ref = useRef<HTMLDivElement>(null);
  const html = convertLatexToHtml(latex, images);

  useEffect(() => {
    if (!ref.current) return;
    ref.current.innerHTML = html;
    renderMathInElement(ref.current, {
      delimiters: KATEX_DELIMITERS,
      macros: KATEX_MACROS,
      throwOnError: false,
    });
  }, [html]);

  return <div ref={ref} className={`latex-content ${className}`} />;
}
