/**
 * Port JS du LatexToHtmlConverter PHP.
 * Transforme les commandes LaTeX custom en HTML avant le rendu KaTeX.
 *
 * Pipeline :
 * 1. Sauvegarder les blocs mathématiques ($$, \[, \begin{...}, $, \() pour les protéger
 * 2. Appliquer les remplacements texte (\\→<br>, \graph, listes, sections…)
 * 3. Restaurer les blocs mathématiques
 *
 * Variants :
 * - 'exercise' (défaut) : tabularx → <span>, center → latex-center
 * - 'problem'           : tabularx → <table>, center → latex latex-center
 */

export type LatexVariant = 'exercise' | 'problem';

const BASE_REPLACEMENTS: [RegExp, string][] = [
  [/\\begin\{itemize\}/g, '<ul>'],
  [/\\end\{itemize\}/g, '</ul>'],
  [/\\begin\{enumerate\}/g, '<ol>'],
  [/\\end\{enumerate\}/g, '</ol>'],
  [/\\item/g, '<li>'],
  [/\\begin\{minipage\}/g, "<div class='latex-minipage'>"],
  [/\\end\{minipage\}/g, '</div>'],
  [/\\begin\{boxed\}/g, "<span class='latex latex-boxed'>"],
  [/\\end\{boxed\}/g, '</span>'],
  [/\\hline/g, '<hr>'],
  [/\\renewcommand\\arraystretch\{0\.9\}/g, ''],
  [
    /\\PA\{(.*?)\}/g,
    "<div class='latex latex-center'><span class='textbf'>Première partie $1</span></div>",
  ],
  [/\\PA/g, "<div class='latex latex-center'><span class='textbf'>Première partie</span></div>"],
  [
    /\\PB\{(.*?)\}/g,
    "<div class='latex latex-center'><span class='textbf'>Deuxième partie $1</span></div>",
  ],
  [/\\PB/g, "<div class='latex latex-center'><span class='textbf'>Deuxième partie</span></div>"],
  [
    /\\PC\{(.*?)\}/g,
    "<div class='latex latex-center'><span class='textbf'>Troisième partie $1</span></div>",
  ],
  [/\\PC/g, "<div class='latex latex-center'><span class='textbf'>Troisième partie</span></div>"],
  [/\\(textbf|textit|texttt|textup)\{(.*?)\}/g, "<span class='$1'>$2</span>"],
];

const CUSTOM_COMMANDS: [string, string][] = [
  ['\\enmb', "<ol class='enumb'>"],
  ['\\fenmb', '</ol>'],
  ['\\enm', '<ol>'],
  ['\\fenm', '</ol>'],
  ['\\itm', "<ul class='point'>"],
  ['\\fitm', '</ul>'],
];

export function convertLatexToHtml(
  latex: string,
  images: Record<string, string> = {},
  variant: LatexVariant = 'exercise'
): string {
  // 1. Nettoyer les espaces insécables
  let html = latex.replace(/\xc2\xa0/g, ' ');

  // 2. Sauvegarder les blocs math pour les protéger des remplacements texte.
  //    Placeholder : null byte via String.fromCharCode (évite no-control-regex lint).
  const NUL = String.fromCharCode(0);
  const saved: string[] = [];
  const save = (s: string): string => {
    saved.push(s);
    return `${NUL}MATH_${saved.length - 1}${NUL}`;
  };

  // Ordre important : display math avant inline math pour éviter les faux matchs
  html = html.replace(/\$\$[\s\S]*?\$\$/g, save);
  html = html.replace(/\\\[[\s\S]*?\\\]/g, save);
  html = html.replace(/\\begin\{[^}]+\}[\s\S]*?\\end\{[^}]+\}/g, save);
  html = html.replace(/\\\([\s\S]*?\\\)/g, save);
  html = html.replace(/\$[^$\n]+?\$/g, save);
  // Commandes display standalone (pas de right delimiter — on protège jusqu'à fin de "mot" LaTeX)
  html = html.replace(/\\(?:dfrac|tfrac|frac|Frac|boxed)\{[^}]*\}/g, save);

  // 3. Remplacements texte (les blocs math sont protégés)

  // \\ → saut de ligne (séparateur de paragraphes LaTeX en mode texte)
  html = html.replace(/\\\\[ \t]*/g, '<br>');

  // \graph nouvelle syntaxe : \graph{identifier}{width}{description}
  html = html.replace(
    /\\graph\{([a-zA-Z0-9_-]+)\}\{([0-9.]+)\}\{(.*?)\}/g,
    (_, id, width, desc) => {
      const src = images[id] ?? null;
      const w = parseFloat(width) * 100;
      if (src)
        return `<div class='latex-center'><img src='${src}' alt='${desc}' class='png' style='width:${w}%'></div>`;
      return `<div class='latex-center latex-img-placeholder text-xs text-text-gray italic'>[Image : ${desc}]</div>`;
    }
  );

  // \graph ancienne syntaxe : \graph{width}{description}  (width est numérique)
  const imageValues = Object.values(images);
  let graphIndex = 0;
  html = html.replace(/\\graph\{([0-9.]+)\}\{(.*?)\}/g, (_, width, desc) => {
    const src = imageValues[graphIndex++] ?? null;
    const w = parseFloat(width) * 100;
    if (src)
      return `<div class='latex-center'><img src='${src}' alt='${desc}' class='png' style='width:${w}%'></div>`;
    return `<div class='latex-center latex-img-placeholder text-xs text-text-gray italic'>[Image : ${desc}]</div>`;
  });

  // Remplacements de base (listes, sections, mise en page…)
  for (const [pattern, replacement] of BASE_REPLACEMENTS) {
    html = html.replace(pattern, replacement);
  }

  // Remplacements variant-dépendants
  if (variant === 'problem') {
    html = html.replace(/\\begin\{center\}/g, "<div class='latex latex-center'>");
    html = html.replace(/\\end\{center\}/g, '</div>');
    html = html.replace(
      /\\begin\{tabularx\}\{(.+?)\}/g,
      "<table class='latex-tabularx' style='width: $1%;'>"
    );
    html = html.replace(/\\end\{tabularx\}/g, '</table>');
  } else {
    // exercise (défaut), quiz, recap
    html = html.replace(/\\begin\{center\}/g, "<div class='latex-center'>");
    html = html.replace(/\\end\{center\}/g, '</div>');
    html = html.replace(
      /\\begin\{tabularx\}\{(.+?)\}/g,
      "<span class='latex latex-tabularx' style='width: $1%;'>"
    );
    html = html.replace(/\\end\{tabularx\}/g, '</span>');
  }

  // Commandes custom (enmb, enm, itm…)
  for (const [command, replacement] of CUSTOM_COMMANDS) {
    html = html.split(command).join(replacement);
  }

  // 4. Restaurer les blocs math
  html = html.replace(new RegExp(`${NUL}MATH_(\\d+)${NUL}`, 'g'), (_, i) => saved[parseInt(i)]);

  return html;
}
