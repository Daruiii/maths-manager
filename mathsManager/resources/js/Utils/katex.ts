/**
 * KaTeX configuration — fusionné depuis _old_js/katex.js
 * Delimiters et macros utilisés dans tout le rendu LaTeX de l'app.
 */

export const KATEX_DELIMITERS = [
  { left: '$$', right: '$$', display: true },
  { left: '\\[', right: '\\]', display: true },
  { left: '$', right: '$', display: false },
  { left: '\\(', right: '\\)', display: false },
  // Commandes display standalone
  { left: '\\quad', right: '', display: true },
  { left: '\\qquad', right: '', display: true },
  { left: '\\dfrac{', right: '}', display: true },
  { left: '\\tfrac{', right: '}', display: false },
  { left: '\\frac{', right: '}', display: true },
  { left: '\\Frac{', right: '}', display: true },
  { left: '\\Sum', right: '', display: true },
  { left: '\\Prod', right: '', display: true },
  { left: '\\boxed{', right: '}', display: true },
  // Environnements
  { left: '\\begin{equation}', right: '\\end{equation}', display: true },
  { left: '\\begin{matrix}', right: '\\end{matrix}', display: true },
  { left: '\\begin{vmatrix}', right: '\\end{vmatrix}', display: true },
  { left: '\\begin{bmatrix}', right: '\\end{bmatrix}', display: true },
  { left: '\\begin{pmatrix}', right: '\\end{pmatrix}', display: true },
  { left: '\\begin{Vmatrix}', right: '\\end{Vmatrix}', display: true },
  { left: '\\begin{cases}', right: '\\end{cases}', display: true },
  { left: '\\begin{align}', right: '\\end{align}', display: true },
  { left: '\\begin{align*}', right: '\\end{align*}', display: true },
  { left: '\\begin{gather}', right: '\\end{gather}', display: true },
  { left: '\\begin{gather*}', right: '\\end{gather*}', display: true },
  { left: '\\begin{split}', right: '\\end{split}', display: true },
  { left: '\\begin{multiline}', right: '\\end{multiline}', display: true },
  { left: '\\begin{multiline*}', right: '\\end{multiline*}', display: true },
  { left: '\\begin{array}', right: '\\end{array}', display: true },
  { left: '\\begin{subequations}', right: '\\end{subequations}', display: true },
  { left: '\\begin{gathered}', right: '\\end{gathered}', display: true },
  { left: '\\begin{smallmatrix}', right: '\\end{smallmatrix}', display: true },
  { left: '\\begin{CD}', right: '\\end{CD}', display: true },
];

export const KATEX_MACROS: Record<string, string> = {
  // Ensembles
  '\\R': '\\mathbb{R}',
  '\\Z': '\\mathbb{Z}',
  '\\N': '\\mathbb{N}',
  '\\Q': '\\mathbb{Q}',
  '\\C': '\\mathbb{C}',
  '\\U': '\\mathbb{U}',

  // Matrices / espaces
  '\\Mnr': '\\mathcal{M}_{n}(\\mathbb{R})',
  '\\M': '\\mathcal{M}',

  // Courbes
  '\\Cf': '\\mathscr{C}_f',
  '\\Cg': '\\mathscr{C}_g',
  '\\Ch': '\\mathscr{C}_h',
  '\\Cn': '\\mathscr{C}_n',
  '\\Cc': '\\mathscr{C}',
  '\\Ccp': '\\mathscr{C}',
  '\\Cu': '\\mathscr{C}_1',
  '\\Cd': '\\mathscr{C}_2',
  '\\Ct': '\\mathscr{C}_3',

  // Analyse
  '\\hdots': '\\ldots',
  '\\Frac': '\\displaystyle \\frac{#1}{#2}',
  '\\Sum': '\\displaystyle \\sum',
  '\\Prod': '\\displaystyle \\prod',
  '\\integrale': '\\displaystyle \\int_{#1}^{#2} #3 \\, \\mathrm{d} #4',
  '\\aire': '\\mathscr{A}',
  '\\airef': '\\mathscr{A}(#1)',

  // Limites
  '\\limn': '\\displaystyle \\lim_{n \\to +\\infty}',
  '\\limplus': '\\displaystyle \\lim_{x \\to +\\infty}',
  '\\limoins': '\\displaystyle \\lim_{x \\to -\\infty}',
  '\\limz': '\\displaystyle \\lim_{x \\to 0}',
  '\\limzp': '\\displaystyle \\lim_{x \\to 0, x > 0}',
  '\\limzm': '\\displaystyle \\lim_{x \\to 0, x < 0}',

  // Suites
  '\\un': '(u_n)',
  '\\vn': '(v_n)',
  '\\wn': '(w_n)',
  '\\an': '(a_n)',
  '\\bn': '(b_n)',
  '\\cn': '(c_n)',
  '\\unp': 'u_{n+1}',
  '\\vnp': 'v_{n+1}',
  '\\wnp': 'w_{n+1}',
  '\\anp': 'a_{n+1}',
  '\\bnp': 'b_{n+1}',
  '\\unpp': 'u_{n+2}',
  '\\vnpp': 'v_{n+2}',
  '\\wnpp': 'w_{n+2}',
  '\\anpp': 'a_{n+2}',
  '\\bnpp': 'b_{n+2}',
  '\\unm': 'u_{n-1}',
  '\\vnm': 'v_{n-1}',
  '\\wnm': 'w_{n-1}',
  '\\anm': 'a_{n-1}',
  '\\bnm': 'b_{n-1}',

  // Quantificateurs / récurrence
  '\\ptn': '\\forall n \\in \\mathbb{N}',
  '\\ptne': '\\forall n \\in \\mathbb{N}^*',
  '\\Pn': '\\mathscr{P}(n)',
  '\\Pnp': '\\mathscr{P}(n+1)',
  '\\Pnpp': '\\mathscr{P}(n+2)',

  // Intervalles / ensembles R
  '\\Rp': '[0;+\\infty[',
  '\\Rpe': ']0;+\\infty[',
  '\\Rm': ']-\\infty;0]',
  '\\Rme': ']-\\infty;0[',
  '\\intf': '\\left[#1; #2 \\right]',
  '\\into': '\\left] #1;#2 \\right[',

  // Géométrie / vecteurs
  '\\vect': '\\overrightarrow{#1}',
  '\\norm': '\\left\\lVert #1 \\right\\rVert',
  '\\scal': '\\overrightarrow{#1} \\cdot \\overrightarrow{#2}',
  '\\reperei': '(O;\\overrightarrow{i},\\overrightarrow{j},\\overrightarrow{k})',
  '\\reperea': '(1;\\overrightarrow{AB},\\overrightarrow{AD},\\overrightarrow{AE})',

  // Valeur absolue / parenthèses
  '\\abs': '\\left\\lvert #1 \\right\\rvert',
  '\\parenthese': '\\left( #1 \\right)',

  // Fractions / angles
  '\\ps': '\\displaystyle \\frac{\\pi}{#1}',
  '\\fp': '\\frac{#1\\pi}{#2}',
  '\\bar': '\\overline{#1}',

  // Divers
  '\\modulo': '\\; [#1]',
  '\\lq': '\\leqslant',
  '\\gq': '\\geqslant',
};
