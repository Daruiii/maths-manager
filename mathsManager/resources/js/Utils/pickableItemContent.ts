import { PickableItem } from '@/types/models';

interface RenderablePickableContent {
  statementHtml: string | null;
  latexStatement: string | null;
  images: Record<string, string>;
}

type ImagePathsInput = Record<string, string> | string | null | undefined;

function parseImagePaths(input: ImagePathsInput): Record<string, string> {
  if (!input) return {};

  if (typeof input === 'string') {
    try {
      const parsed = JSON.parse(input) as unknown;
      if (!parsed || typeof parsed !== 'object' || Array.isArray(parsed)) return {};
      return Object.fromEntries(
        Object.entries(parsed as Record<string, unknown>).filter(
          (_entry): _entry is [string, string] => typeof _entry[1] === 'string'
        )
      );
    } catch {
      return {};
    }
  }

  return input;
}

export function normalizeStoragePath(path: string): string {
  const normalized = path.replace(/\\/g, '/').replace(/^\/+/, '');

  if (normalized.startsWith('http://') || normalized.startsWith('https://')) {
    return normalized;
  }

  if (normalized.startsWith('blob:') || normalized.startsWith('data:')) {
    return normalized;
  }

  if (normalized.startsWith('storage/')) {
    return `/${normalized}`;
  }

  if (normalized.startsWith('public/storage/')) {
    return `/${normalized.replace(/^public\//, '')}`;
  }

  if (normalized.startsWith('public/')) {
    return `/${normalized.replace(/^public\//, '')}`;
  }

  return `/storage/${normalized}`;
}

export function mapItemImagePaths(item: PickableItem): Record<string, string> {
  const imagePaths = parseImagePaths(item.image_paths as ImagePathsInput);
  if (Object.keys(imagePaths).length === 0) return {};

  return Object.fromEntries(
    Object.entries(imagePaths).map(([key, value]) => [key, normalizeStoragePath(value)])
  );
}

export function getRenderablePickableContent(item: PickableItem): RenderablePickableContent {
  const statementHtml =
    item.kind === 'problem' && item.statement && item.statement.trim() !== ''
      ? item.statement
      : null;

  const latexStatement =
    item.latex_statement && item.latex_statement.trim() !== '' ? item.latex_statement : null;

  return {
    statementHtml,
    latexStatement,
    images: mapItemImagePaths(item),
  };
}
