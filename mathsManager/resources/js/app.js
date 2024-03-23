import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
document.addEventListener("DOMContentLoaded", function() {
    var form = document.querySelector("#exerciseForm");
    form.addEventListener("submit", function() {
        document.getElementById("loadingPopup").style.display = "block";
    });
});

window.macros = {
    "\\test" : "HAHAHA LETS GOO",
    "\\hdots" : "\\ldots",
    "\\R": "\\mathbb{R}",
    "\\Z": "\\mathbb{Z}",
    '\\N': '\\mathbb{N}',
    '\\Q': '\\mathbb{Q}',
    '\\C': '\\mathbb{C}',
    '\\U': '\\mathbb{U}',
    '\\Mnr': '\\mathcal{M}_{n}(\\R)',
    '\\M': '\\mathcal{M}',
    '\\Cf': '\\mathscr{C}_f',
    '\\Cg': '\\mathscr{C}_g',
    '\\Ch': '\\mathscr{C}_h',
    '\\Cn': '\\mathscr{C}_n',
    '\\Cc': '\\mathscr{C}',
    '\\Ccp': '\\mathscr{C}',
    '\\Cu': '\\mathscr{C}_1',
    '\\Cd': '\\mathscr{C}_2',
    '\\Ct': '\\mathscr{C}_3',
    '\\integrale': '\\int_{#1}^{#2} #3 \\, \\mathrm{d} #4',
    '\\aire': '\\mathscr{A}',
    '\\airef': '\\mathscr{A}(#1)',
    '\\Pn': '\\mathscr{P}(n)',
    '\\Pnp': '\\mathscr{P}(n+1)',
    '\\Pnpp': '\\mathscr{P}(n+2)',
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
    '\\ptn': '\\forall n \\in \\N',
    '\\ptne': '\\forall n \\in \\N^*',
    '\\limn': '\\lim_{n \\to +\\infty}',
    '\\limplus': '\\lim_{x \\to +\\infty}',
    '\\limoins': '\\lim_{x \\to -\\infty}',
    '\\limz': '\\lim_{x \\to 0}',
    '\\limzp': '\\lim_{x \\to 0, x > 0}',
    '\\limzm': '\\lim_{x \\to 0, x < 0}',
    '\\Frac': '\\displaystyle \\frac{#1}{#2}',
    '\\parenthese': '\\left( #1 \\right)',
    '\\Rp': '[0;+\\infty[',
    '\\Rpe': ']0;+\\infty[',
    '\\Rm': ']-\\infty;0]',
    '\\Rme': ']-\\infty;0[',
    '\\intf': '\\left[#1; #2 \\right]',
    '\\into': '\\left] #1;#2 \\right[',
    '\\vect': '\\overrightarrow{#1}',
    '\\norm': '\\left\\lVert #1 \\right \\rVert',
    '\\scal': '\\vect{#1} \\cdot \\vect{#2}',
    '\\abs': '\\left \\lvert #1 \\right \\lvert',
    '\\modulo': '\\; [#1]',
    '\\ps': '\\frac{\\pi}{#1}',
    '\\fp': '\\frac{#1\\pi}{#2}',
    '\\bar': '\\overline{#1}',
    '\\reperei': '(O;\\vect{i},\\vect{j},\\vect{k})',
    '\\reperea': '(1;\\vect{AB},\\vect{AD},\\vect{AE})'
    // Assurez-vous d'ajouter le reste de vos macros ici
};