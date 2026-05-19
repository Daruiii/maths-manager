import { describe, expect, it } from 'vitest';
import { KATEX_MACROS } from '@/Utils/katex';
import { collectContentMacroIssues } from '@/Utils/contentMacroValidation';

describe('collectContentMacroIssues', () => {
  it('returns no issues for valid custom macro usage', () => {
    const latex = String.raw`On a \Frac{a+b}{c} et \integrale{0}{1}{x^2}{x}.`;

    const issues = collectContentMacroIssues(latex, KATEX_MACROS);

    expect(issues).toHaveLength(0);
  });

  it('reports arity issue when a custom macro misses arguments', () => {
    const latex = String.raw`\Frac{a+b}`;

    const issues = collectContentMacroIssues(latex, KATEX_MACROS);

    expect(issues).toHaveLength(1);
    expect(issues[0].message).toContain('La macro \\Frac attend 2 argument(s)');
  });

  it('reports disallowed macro definition commands', () => {
    const latex = String.raw`\newcommand{\foo}{x} et \renewcommand{\bar}{y}`;

    const issues = collectContentMacroIssues(latex, KATEX_MACROS);

    expect(issues.some((issue) => issue.key.includes('disallowed-macro-definition'))).toBe(true);
  });

  it('does not flag standard latex commands that are not custom macros', () => {
    const latex = String.raw`\frac{a}{b} + \sum_{n=0}^{10} n`;

    const issues = collectContentMacroIssues(latex, KATEX_MACROS);

    expect(issues).toHaveLength(0);
  });

  it('does not match custom macro names as prefix of longer commands', () => {
    const latex = String.raw`\FracExtra{a}{b}`;

    const issues = collectContentMacroIssues(latex, KATEX_MACROS);

    expect(issues).toHaveLength(0);
  });

  it('does not flag global macros when scope is empty (private with no teacher macros)', () => {
    const latex = String.raw`\Frac{a}{b}`;

    const issues = collectContentMacroIssues(latex, {});

    // \Frac is not in the empty macro set — no arity check performed, no issue raised
    expect(issues).toHaveLength(0);
  });

  it('validates arity of teacher-defined macros in private scope', () => {
    const teacherMacros = { '\\myMacro': '#1 + #2' };
    const latex = String.raw`\myMacro{a}`;

    const issues = collectContentMacroIssues(latex, teacherMacros);

    expect(issues).toHaveLength(1);
    expect(issues[0].message).toContain('La macro \\myMacro attend 2 argument(s)');
  });
});
