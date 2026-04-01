import { useState, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { DSPreviewItem } from '@/types/models';
import { PageProps } from '@/types';
import { DS_DEFAULT_TITLE, DS_DEFAULT_LEVEL, DS_DEFAULT_INSTRUCTIONS } from '@/Constants/ds';

const DRAFT_TTL_MS = 48 * 60 * 60 * 1000; // 48h

interface Draft {
  previewItems: DSPreviewItem[];
  dsTitle: string;
  dsLevel: string;
  dsInstructions: string;
  expiresAt: number;
}

function draftKey(userId: number) {
  return `ds_builder_draft_${userId}`;
}

function readDraft(userId: number): Draft | null {
  try {
    const raw = localStorage.getItem(draftKey(userId));
    if (!raw) return null;
    const draft = JSON.parse(raw) as Draft;
    if (Date.now() > draft.expiresAt) {
      localStorage.removeItem(draftKey(userId));
      return null;
    }
    return draft;
  } catch {
    return null;
  }
}

export function makeItemUid(kind: string, id: number, index: number) {
  return `${kind}-${id}-${index}-${Date.now()}`;
}

export function useDSBuilderDraft() {
  const { props } = usePage<PageProps>();
  const userId = props.auth.user!.id;

  const init = readDraft(userId);

  const [hadDraftOnMount] = useState(() => !!init?.previewItems?.length);
  const [previewItems, setPreviewItems] = useState<DSPreviewItem[]>(init?.previewItems ?? []);
  const [dsTitle, setDsTitle] = useState(init?.dsTitle ?? DS_DEFAULT_TITLE);
  const [dsLevel, setDsLevel] = useState(init?.dsLevel ?? DS_DEFAULT_LEVEL);
  const [dsInstructions, setDsInstructions] = useState(
    init?.dsInstructions ?? DS_DEFAULT_INSTRUCTIONS
  );

  useEffect(() => {
    localStorage.setItem(
      draftKey(userId),
      JSON.stringify({
        previewItems,
        dsTitle,
        dsLevel,
        dsInstructions,
        expiresAt: Date.now() + DRAFT_TTL_MS,
      })
    );
  }, [userId, previewItems, dsTitle, dsLevel, dsInstructions]);

  const resetAll = () => {
    localStorage.removeItem(draftKey(userId));
    setPreviewItems([]);
    setDsTitle(DS_DEFAULT_TITLE);
    setDsLevel(DS_DEFAULT_LEVEL);
    setDsInstructions(DS_DEFAULT_INSTRUCTIONS);
  };

  return {
    previewItems,
    setPreviewItems,
    dsTitle,
    setDsTitle,
    dsLevel,
    setDsLevel,
    dsInstructions,
    setDsInstructions,
    hadDraftOnMount,
    resetAll,
  };
}
