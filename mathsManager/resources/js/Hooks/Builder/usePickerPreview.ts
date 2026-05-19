import { useCallback, useEffect, useRef, useState } from 'react';
import { PickableItem } from '@/types/models';
import { PickerPreviewState, PreviewTriggerSource } from '@/types/ui';

const PREVIEW_OPEN_DELAY_MS = 120;
const PREVIEW_CLOSE_DELAY_MS = 100;
const TOUCH_MEDIA_QUERY = '(hover: none), (pointer: coarse)';
const DESKTOP_PREVIEW_MEDIA_QUERY = '(min-width: 1024px)'; // Tailwind lg

interface UsePickerPreviewOptions {
  onToggle: (item: PickableItem) => void;
}

export function usePickerPreview({ onToggle }: UsePickerPreviewOptions) {
  const [previewState, setPreviewState] = useState<PickerPreviewState | null>(null);
  const [isTouch, setIsTouch] = useState<boolean>(
    typeof window !== 'undefined'
      ? (window.matchMedia?.(TOUCH_MEDIA_QUERY)?.matches ?? false)
      : false
  );
  const [isWideScreen, setIsWideScreen] = useState<boolean>(
    typeof window !== 'undefined'
      ? (window.matchMedia?.(DESKTOP_PREVIEW_MEDIA_QUERY)?.matches ?? false)
      : false
  );

  const openTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);
  const closeTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  const cancelCloseTimer = useCallback(() => {
    if (closeTimerRef.current) clearTimeout(closeTimerRef.current);
  }, []);

  const canPreview = !isTouch && isWideScreen;

  const handlePreview = useCallback(
    (item: PickableItem, rect: DOMRect, source: PreviewTriggerSource) => {
      if (!canPreview) {
        return;
      }

      cancelCloseTimer();
      if (openTimerRef.current) clearTimeout(openTimerRef.current);

      if (source === 'hover') {
        openTimerRef.current = setTimeout(() => {
          setPreviewState({ item, rect });
        }, PREVIEW_OPEN_DELAY_MS);
        return;
      }

      setPreviewState({ item, rect });
    },
    [canPreview, cancelCloseTimer]
  );

  const scheduleClose = useCallback(() => {
    if (openTimerRef.current) clearTimeout(openTimerRef.current);
    closeTimerRef.current = setTimeout(() => setPreviewState(null), PREVIEW_CLOSE_DELAY_MS);
  }, []);

  const handleClose = useCallback(() => {
    if (openTimerRef.current) clearTimeout(openTimerRef.current);
    cancelCloseTimer();
    setPreviewState(null);
  }, [cancelCloseTimer]);

  const handleToggle = useCallback(
    (item: PickableItem) => {
      onToggle(item);
      if (openTimerRef.current) clearTimeout(openTimerRef.current);
      cancelCloseTimer();
      setPreviewState(null);
    },
    [cancelCloseTimer, onToggle]
  );

  useEffect(() => {
    if (typeof window === 'undefined') return;

    const mediaQuery = window.matchMedia(TOUCH_MEDIA_QUERY);
    const updateTouchState = () => setIsTouch(mediaQuery.matches);

    updateTouchState();

    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener('change', updateTouchState);
      return () => mediaQuery.removeEventListener('change', updateTouchState);
    }

    mediaQuery.addListener(updateTouchState);
    return () => mediaQuery.removeListener(updateTouchState);
  }, []);

  useEffect(() => {
    if (typeof window === 'undefined') return;

    const mediaQuery = window.matchMedia(DESKTOP_PREVIEW_MEDIA_QUERY);
    const updateWidthState = () => setIsWideScreen(mediaQuery.matches);

    updateWidthState();

    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener('change', updateWidthState);
      return () => mediaQuery.removeEventListener('change', updateWidthState);
    }

    mediaQuery.addListener(updateWidthState);
    return () => mediaQuery.removeListener(updateWidthState);
  }, []);

  useEffect(() => {
    if (canPreview) return;
    setPreviewState(null);
  }, [canPreview]);

  useEffect(() => {
    return () => {
      if (openTimerRef.current) clearTimeout(openTimerRef.current);
      if (closeTimerRef.current) clearTimeout(closeTimerRef.current);
    };
  }, []);

  return {
    isTouch,
    isWideScreen,
    canPreview,
    previewState,
    handlePreview,
    scheduleClose,
    handleClose,
    handleToggle,
    cancelCloseTimer,
  };
}
