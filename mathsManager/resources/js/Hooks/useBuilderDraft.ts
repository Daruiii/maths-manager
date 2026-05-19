import { useState, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { DSPreviewItem, TemplatePayload } from '@/types/models';
import { PageProps } from '@/types';

export function makeItemUid(kind: string, id: number, index: number) {
  return `${kind}-${id}-${index}-${Date.now()}`;
}

const DRAFT_TTL_MS = 48 * 60 * 60 * 1000; // 48h

interface BuilderDefaults {
  title: string;
  level: string;
  instructions: string;
}

interface Draft {
  previewItems: DSPreviewItem[];
  title: string;
  level: string;
  instructions: string;
  expiresAt: number;
}

function draftKey(prefix: string, userId: number) {
  return `${prefix}_builder_draft_${userId}`;
}

function readDraft(prefix: string, userId: number): Draft | null {
  try {
    const raw = localStorage.getItem(draftKey(prefix, userId));
    if (!raw) return null;
    const draft = JSON.parse(raw) as Draft;
    if (Date.now() > draft.expiresAt) {
      localStorage.removeItem(draftKey(prefix, userId));
      return null;
    }
    return draft;
  } catch {
    return null;
  }
}

export function useBuilderDraft(
  prefix: string,
  defaults: BuilderDefaults,
  initialTemplate?: TemplatePayload
) {
  const { props } = usePage<PageProps>();
  const userId = props.auth.user!.id;

  const init = initialTemplate ? null : readDraft(prefix, userId);

  const [hadDraftOnMount] = useState(() => !!init?.previewItems?.length);
  const [hadTemplateLoad] = useState(() => !!initialTemplate?.items?.length);
  const [previewItems, setPreviewItems] = useState<DSPreviewItem[]>(
    initialTemplate?.items ?? init?.previewItems ?? []
  );
  const [title, setTitle] = useState(initialTemplate?.title ?? init?.title ?? defaults.title);
  const [level, setLevel] = useState(initialTemplate?.level ?? init?.level ?? defaults.level);
  const [instructions, setInstructions] = useState(
    initialTemplate?.instructions ?? init?.instructions ?? defaults.instructions
  );

  // Si chargement depuis template, vide le brouillon localStorage pour éviter un conflit au prochain rechargement
  useEffect(() => {
    if (initialTemplate) {
      localStorage.removeItem(draftKey(prefix, userId));
    }
  }, []);

  useEffect(() => {
    localStorage.setItem(
      draftKey(prefix, userId),
      JSON.stringify({
        previewItems,
        title,
        level,
        instructions,
        expiresAt: Date.now() + DRAFT_TTL_MS,
      })
    );
  }, [prefix, userId, previewItems, title, level, instructions]);

  const resetAll = () => {
    localStorage.removeItem(draftKey(prefix, userId));
    setPreviewItems([]);
    setTitle(defaults.title);
    setLevel(defaults.level);
    setInstructions(defaults.instructions);
  };

  return {
    previewItems,
    setPreviewItems,
    title,
    setTitle,
    level,
    setLevel,
    instructions,
    setInstructions,
    hadDraftOnMount,
    hadTemplateLoad,
    resetAll,
  };
}
