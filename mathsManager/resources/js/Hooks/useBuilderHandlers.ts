import { useState, useCallback, Dispatch, SetStateAction } from 'react';
import { DSPreviewItem, PickableItem } from '@/types/models';
import { makeItemUid } from '@/Hooks/useBuilderDraft';

export type MobileTab = 'picker' | 'preview' | 'sommaire';

export function useBuilderHandlers(setPreviewItems: Dispatch<SetStateAction<DSPreviewItem[]>>) {
  const [mobileTab, setMobileTab] = useState<MobileTab>('picker');

  const handleToggle = useCallback(
    (item: PickableItem) => {
      setPreviewItems((prev) => {
        const existingIndex = prev.findIndex(
          (i) => i.item.kind === item.kind && i.item.id === item.id
        );
        if (existingIndex !== -1) {
          return prev.filter((_, idx) => idx !== existingIndex);
        }
        setMobileTab('preview');
        return [...prev, { uid: makeItemUid(item.kind, item.id, prev.length), item }];
      });
    },
    [setPreviewItems]
  );

  const handleRemove = useCallback(
    (uid: string) => setPreviewItems((prev) => prev.filter((i) => i.uid !== uid)),
    [setPreviewItems]
  );

  const handleReorder = useCallback(
    (items: DSPreviewItem[]) => setPreviewItems(items),
    [setPreviewItems]
  );

  return { mobileTab, setMobileTab, handleToggle, handleRemove, handleReorder };
}
