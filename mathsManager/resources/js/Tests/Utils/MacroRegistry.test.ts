import { describe, expect, it } from 'vitest';
import { KATEX_MACROS } from '@/Utils/katex';
import { getMacrosForContent } from '@/Utils/MacroRegistry';

describe('getMacrosForContent', () => {
  it('global-content returns KATEX_MACROS', () => {
    const macros = getMacrosForContent('global-content');

    expect(macros).toBe(KATEX_MACROS);
  });

  it('global-content ignores teacherMacros even if provided', () => {
    const teacherMacros = { '\\myMacro': '#1' };

    const macros = getMacrosForContent('global-content', teacherMacros);

    expect(macros).toBe(KATEX_MACROS);
    expect(macros['\\myMacro']).toBeUndefined();
  });

  it('private-content with teacher macros returns only teacher macros', () => {
    const teacherMacros = { '\\myMacro': '#1 + #2' };

    const macros = getMacrosForContent('private-content', teacherMacros);

    expect(macros).toBe(teacherMacros);
    // Global macro must NOT be present
    expect(macros['\\R']).toBeUndefined();
    expect(macros['\\Frac']).toBeUndefined();
  });

  it('private-content with null returns empty macros', () => {
    const macros = getMacrosForContent('private-content', null);

    expect(macros).toEqual({});
  });

  it('private-content with undefined returns empty macros', () => {
    const macros = getMacrosForContent('private-content');

    expect(macros).toEqual({});
  });

  it('global macro \\R is absent from private scope', () => {
    const macros = getMacrosForContent('private-content', { '\\custom': 'x' });

    expect(macros['\\R']).toBeUndefined();
  });

  it('teacher macro is absent from global scope', () => {
    const macros = getMacrosForContent('global-content', { '\\custom': 'x' });

    expect(macros['\\custom']).toBeUndefined();
  });
});
