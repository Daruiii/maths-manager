import { useState, useEffect } from 'react';
import { DSPreviewItem } from '@/types/models';
import { DS_DEFAULT_TITLE, DS_DEFAULT_LEVEL, DS_DEFAULT_INSTRUCTIONS } from '@/Constants/ds';

const DRAFT_KEY = 'ds_builder_draft';

interface Draft {
  previewItems: DSPreviewItem[];
  dsTitle: string;
  dsLevel: string;
  dsInstructions: string;
}

function readDraft(): Draft | null {
  try {
    const raw = localStorage.getItem(DRAFT_KEY);
    if (!raw) return null;
    return JSON.parse(raw) as Draft;
  } catch {
    return null;
  }
}

export function makeItemUid(kind: string, id: number, index: number) {
  return `${kind}-${id}-${index}-${Date.now()}`;
}

export function useDSBuilderDraft() {
  // Lazy initialiser — readDraft() appelé une seule fois au mount
  const [init] = useState(readDraft);

  const [hadDraftOnMount] = useState(() => !!init?.previewItems?.length);
  const [previewItems, setPreviewItems] = useState<DSPreviewItem[]>(init?.previewItems ?? []);
  const [dsTitle, setDsTitle] = useState(init?.dsTitle ?? DS_DEFAULT_TITLE);
  const [dsLevel, setDsLevel] = useState(init?.dsLevel ?? DS_DEFAULT_LEVEL);
  const [dsInstructions, setDsInstructions] = useState(
    init?.dsInstructions ?? DS_DEFAULT_INSTRUCTIONS
  );

  useEffect(() => {
    localStorage.setItem(
      DRAFT_KEY,
      JSON.stringify({ previewItems, dsTitle, dsLevel, dsInstructions })
    );
  }, [previewItems, dsTitle, dsLevel, dsInstructions]);

  const resetAll = () => {
    localStorage.removeItem(DRAFT_KEY);
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
