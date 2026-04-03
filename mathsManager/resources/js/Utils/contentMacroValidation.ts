import { KATEX_MACROS } from '@/Utils/katex';

export interface ContentMacroIssue {
  key: string;
  message: string;
  start: number;
  end: number;
}

const DISALLOWED_MACRO_DEFINITION_REGEX = /\\(newcommand|renewcommand|def)\b/g;

const CUSTOM_MACROS_REQUIRED_ARGS = Object.entries(KATEX_MACROS)
  .map(([macro, definition]) => ({
    macro,
    requiredArgs: getRequiredArgumentCount(definition),
  }))
  .filter((entry) => entry.requiredArgs > 0);

function getRequiredArgumentCount(definition: string): number {
  const placeholders = definition.match(/#([1-9])/g);
  if (!placeholders || placeholders.length === 0) return 0;

  return placeholders.reduce((max, placeholder) => {
    const value = Number(placeholder.slice(1));
    return Number.isFinite(value) ? Math.max(max, value) : max;
  }, 0);
}

function escapeForRegex(input: string): string {
  return input.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function consumeBracedArgument(latex: string, from: number): number | null {
  let index = from;

  while (index < latex.length && /\s/.test(latex[index])) {
    index += 1;
  }

  if (latex[index] !== '{') return null;

  let depth = 0;
  for (let cursor = index; cursor < latex.length; cursor += 1) {
    const char = latex[cursor];
    if (char === '{') depth += 1;
    if (char === '}') {
      depth -= 1;
      if (depth === 0) {
        return cursor + 1;
      }
    }
  }

  return null;
}

export function collectContentMacroIssues(latex: string): ContentMacroIssue[] {
  const issues: ContentMacroIssue[] = [];

  for (const match of latex.matchAll(DISALLOWED_MACRO_DEFINITION_REGEX)) {
    const start = match.index;
    const command = match[0];
    if (start === undefined || !command) continue;

    issues.push({
      key: `disallowed-macro-definition-${start}`,
      message:
        'Les définitions de macros (\\newcommand, \\renewcommand, \\def) ne sont pas autorisées ici.',
      start,
      end: start + command.length,
    });
  }

  for (const { macro, requiredArgs } of CUSTOM_MACROS_REQUIRED_ARGS) {
    const usageRegex = new RegExp(`${escapeForRegex(macro)}(?![a-zA-Z])`, 'g');

    for (const usage of latex.matchAll(usageRegex)) {
      const start = usage.index;
      if (start === undefined) continue;

      let cursor = start + macro.length;
      let providedArgs = 0;

      while (providedArgs < requiredArgs) {
        const next = consumeBracedArgument(latex, cursor);
        if (next === null) break;
        providedArgs += 1;
        cursor = next;
      }

      if (providedArgs < requiredArgs) {
        issues.push({
          key: `macro-arity-${macro}-${start}`,
          message: `La macro ${macro} attend ${requiredArgs} argument(s), mais ${providedArgs} ont été détecté(s).`,
          start,
          end: start + macro.length,
        });
      }
    }
  }

  return issues;
}
