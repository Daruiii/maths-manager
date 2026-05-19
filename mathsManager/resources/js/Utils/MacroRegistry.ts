import { KATEX_MACROS } from '@/Utils/katex';

export type MacroScope = 'global-content' | 'private-content';

/**
 * Unique entry point for resolving KaTeX macros by content scope.
 *
 * global-content  → KATEX_MACROS (app-wide macros, no teacher macros)
 * private-content → teacher's personal macros only (no global fallback)
 *
 * No cross-scope fallback — this is intentional and enforced by the contract:
 * a private exercise must not silently resolve global macros, and vice-versa.
 */
export function getMacrosForContent(
  scope: MacroScope,
  teacherMacros?: Record<string, string> | null
): Record<string, string> {
  if (scope === 'global-content') {
    return KATEX_MACROS;
  }
  return teacherMacros ?? {};
}
