import { describe, expect, it } from 'vitest';
import { convertLatexToHtml } from '@/Utils/latexConverter';

describe('convertLatexToHtml', () => {
  it('converts center text blocks in exercise variant', () => {
    const input = '\\begin{center}\\textbf{Première partie}\\end{center}';

    const html = convertLatexToHtml(input, {}, 'exercise');

    expect(html).toContain("<div class='latex-center'>");
    expect(html).toContain("<span class='textbf'>Première partie</span>");
    expect(html).not.toContain('\\begin{center}');
  });

  it('preserves KaTeX math environments while converting non-math blocks', () => {
    const input = '\\begin{align*}a&=b\\end{align*}\\begin{itemize}\\item test\\end{itemize}';

    const html = convertLatexToHtml(input, {}, 'exercise');

    expect(html).toContain('\\begin{align*}a&=b\\end{align*}');
    expect(html).toContain('<ul>');
    expect(html).toContain('<li> test</ul>');
  });
});
