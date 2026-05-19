import { createPortal } from 'react-dom';
import PickerItemPreview from '@/Components/Features/Builder/PickerItemPreview';
import { PickerPreviewState } from '@/types/ui';

interface Props {
  isTouch: boolean;
  previewState: PickerPreviewState | null;
  onClose: () => void;
  onMouseEnter: () => void;
  onMouseLeave: () => void;
}

const PREVIEW_TOP_OFFSET = 64;
const PREVIEW_BOTTOM_MARGIN = 16;
const PREVIEW_WIDTH = 360;

export default function PickerPreviewPortal({
  isTouch,
  previewState,
  onClose,
  onMouseEnter,
  onMouseLeave,
}: Props) {
  if (!previewState || isTouch) return null;

  const previewTop = PREVIEW_TOP_OFFSET;
  const previewLeft = Math.min(previewState.rect.right + 8, window.innerWidth - PREVIEW_WIDTH - 16);
  const previewAvailableHeight = window.innerHeight - PREVIEW_TOP_OFFSET - PREVIEW_BOTTOM_MARGIN;
  const previewAvailableWidth = Math.max(280, window.innerWidth - previewLeft - 16);
  const previewMaxHeight = Math.min(500, window.innerHeight - 80, previewAvailableHeight);

  return createPortal(
    <div
      style={{
        position: 'fixed',
        left: previewLeft,
        top: previewTop,
        width: PREVIEW_WIDTH,
        maxWidth: previewAvailableWidth,
        maxHeight: Math.max(240, previewMaxHeight),
        display: 'flex',
        flexDirection: 'column',
        minHeight: 0,
        zIndex: 50,
      }}
      onMouseEnter={onMouseEnter}
      onMouseLeave={onMouseLeave}
    >
      <PickerItemPreview item={previewState.item} onClose={onClose} />
    </div>,
    document.body
  );
}
