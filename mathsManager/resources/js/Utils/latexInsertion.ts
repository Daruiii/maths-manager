export interface ContextualInsertionResult {
  insert: string;
  cursorAnchor: number;
}

export interface MissingGraphReference {
  id: string;
  idStart: number;
  idEnd: number;
}

const GRAPH_COMMAND_REGEX = /\\graph\{([a-zA-Z0-9_-]+)\}\{[0-9.]+\}\{.*?\}/g;

export function buildGraphSnippet(
  imageName: string,
  width = 0.5,
  description = 'Description'
): string {
  return `\\graph{${imageName}}{${width}}{${description}}`;
}

export function extractGraphImageIds(latex: string): string[] {
  const ids: string[] = [];
  for (const match of latex.matchAll(GRAPH_COMMAND_REGEX)) {
    if (match[1]) ids.push(match[1]);
  }
  return ids;
}

export function findMissingGraphImageIds(latex: string, images: Record<string, string>): string[] {
  const imageIds = new Set(Object.keys(images));
  const missing = new Set<string>();

  for (const id of extractGraphImageIds(latex)) {
    if (!imageIds.has(id)) missing.add(id);
  }

  return Array.from(missing);
}

export function findMissingGraphReferences(
  latex: string,
  images: Record<string, string>
): MissingGraphReference[] {
  const imageIds = new Set(Object.keys(images));
  const missingReferences: MissingGraphReference[] = [];

  for (const match of latex.matchAll(GRAPH_COMMAND_REGEX)) {
    const commandStart = match.index;
    const id = match[1];

    if (commandStart === undefined || !id || imageIds.has(id)) continue;

    const idOffset = match[0].indexOf(id);
    if (idOffset === -1) continue;

    const idStart = commandStart + idOffset;
    const idEnd = idStart + id.length;

    missingReferences.push({
      id,
      idStart,
      idEnd,
    });
  }

  return missingReferences;
}

/**
 * Construit une insertion de snippet qui s'assure d'être entourée de nouvelles lignes si nécessaire.
 * Utile pour éviter de coller du LaTeX au milieu d'une ligne existante, ce qui pourrait causer des problèmes de rendu.
 *
 * @param docText Le texte complet du document actuel.
 * @param from La position d'insertion dans le document.
 * @param snippet Le snippet à insérer (ex: "\\frac{a}{b}").
 * @returns Un objet contenant la chaîne à insérer et la position du curseur après insertion.
 */

export function buildContextualSnippetInsertion(
  docText: string,
  from: number,
  snippet: string
): ContextualInsertionResult {
  const beforeChar = from > 0 ? docText[from - 1] : '';
  const afterChar = from < docText.length ? docText[from] : '';

  const needsLeadingNewline = Boolean(beforeChar && beforeChar !== '\n');
  const needsTrailingNewline = Boolean(afterChar && afterChar !== '\n');

  const insert = `${needsLeadingNewline ? '\n' : ''}${snippet}${needsTrailingNewline ? '\n' : ''}`;
  const cursorAnchor = from + (needsLeadingNewline ? 1 : 0) + snippet.length;

  return { insert, cursorAnchor };
}
