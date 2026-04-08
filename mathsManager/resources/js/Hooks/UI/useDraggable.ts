import { useState, useEffect, useRef, useCallback } from 'react';

const ELEMENT_SIZE = 56; // FAB size in px

interface Position {
  x: number;
  y: number;
}

interface UseDraggableOptions {
  margin?: number; // min distance from window edges
  storageKey?: string; // localStorage key to persist position
  onDragStart?: () => void;
  onDragEnd?: () => void;
}

interface UseDraggableReturn {
  position: Position;
  isDragging: boolean;
  hasMoved: boolean;
  handleMouseDown: (e: React.MouseEvent) => void;
  handleTouchStart: (e: React.TouchEvent) => void;
}

function getDefaultPosition(margin: number): Position {
  return {
    x: window.innerWidth - ELEMENT_SIZE - margin,
    y: window.innerHeight - ELEMENT_SIZE - margin,
  };
}

function loadPosition(storageKey: string, margin: number): Position {
  try {
    const saved = localStorage.getItem(storageKey);
    if (!saved) return getDefaultPosition(margin);
    const parsed = JSON.parse(saved) as Partial<Position>;
    if (typeof parsed.x !== 'number' || typeof parsed.y !== 'number') {
      return getDefaultPosition(margin);
    }
    // Re-clamp in case window was resized since last visit
    return {
      x: Math.max(margin, Math.min(window.innerWidth - ELEMENT_SIZE - margin, parsed.x)),
      y: Math.max(margin, Math.min(window.innerHeight - ELEMENT_SIZE - margin, parsed.y)),
    };
  } catch {
    return getDefaultPosition(margin);
  }
}

function savePosition(storageKey: string, position: Position): void {
  try {
    localStorage.setItem(storageKey, JSON.stringify(position));
  } catch {
    // localStorage can be unavailable (private mode, storage full) — fail silently
  }
}

export function useDraggable({
  margin = 8,
  storageKey,
  onDragStart,
  onDragEnd,
}: UseDraggableOptions = {}): UseDraggableReturn {
  const [position, setPosition] = useState<Position>({ x: 0, y: 0 });
  const [isDragging, setIsDragging] = useState(false);
  const [hasMoved, setHasMoved] = useState(false);

  const dragStartRef = useRef<{
    clientX: number;
    clientY: number;
    elX: number;
    elY: number;
  } | null>(null);

  // Initialize: restore from localStorage or default to bottom-right
  useEffect(() => {
    const initial = storageKey ? loadPosition(storageKey, margin) : getDefaultPosition(margin);
    setPosition(initial);
  }, [margin, storageKey]);

  const clamp = useCallback(
    (x: number, y: number): Position => ({
      x: Math.max(margin, Math.min(window.innerWidth - ELEMENT_SIZE - margin, x)),
      y: Math.max(margin, Math.min(window.innerHeight - ELEMENT_SIZE - margin, y)),
    }),
    [margin]
  );

  const startDrag = useCallback(
    (clientX: number, clientY: number) => {
      dragStartRef.current = { clientX, clientY, elX: position.x, elY: position.y };
      setHasMoved(false);
      setIsDragging(true);
      onDragStart?.();
    },
    [position, onDragStart]
  );

  const moveDrag = useCallback(
    (clientX: number, clientY: number) => {
      if (!dragStartRef.current) return;
      const dx = clientX - dragStartRef.current.clientX;
      const dy = clientY - dragStartRef.current.clientY;
      if (Math.abs(dx) > 4 || Math.abs(dy) > 4) setHasMoved(true);
      setPosition(clamp(dragStartRef.current.elX + dx, dragStartRef.current.elY + dy));
    },
    [clamp]
  );

  const endDrag = useCallback(() => {
    setIsDragging(false);
    dragStartRef.current = null;
    // Persist final position
    if (storageKey) {
      setPosition((prev) => {
        savePosition(storageKey, prev);
        return prev;
      });
    }
    onDragEnd?.();
  }, [storageKey, onDragEnd]);

  // ── Mouse events ─────────────────────────────────────────────────────────────
  const handleMouseDown = useCallback(
    (e: React.MouseEvent) => {
      e.preventDefault();
      startDrag(e.clientX, e.clientY);
    },
    [startDrag]
  );

  useEffect(() => {
    if (!isDragging) return;
    const onMove = (e: MouseEvent) => moveDrag(e.clientX, e.clientY);
    const onUp = () => endDrag();
    document.addEventListener('mousemove', onMove);
    document.addEventListener('mouseup', onUp);
    return () => {
      document.removeEventListener('mousemove', onMove);
      document.removeEventListener('mouseup', onUp);
    };
  }, [isDragging, moveDrag, endDrag]);

  // ── Touch events ─────────────────────────────────────────────────────────────
  const handleTouchStart = useCallback(
    (e: React.TouchEvent) => {
      const touch = e.touches[0];
      startDrag(touch.clientX, touch.clientY);
    },
    [startDrag]
  );

  useEffect(() => {
    if (!isDragging) return;
    const onMove = (e: globalThis.TouchEvent) => {
      const touch = e.touches[0];
      moveDrag(touch.clientX, touch.clientY);
    };
    const onEnd = () => endDrag();
    document.addEventListener('touchmove', onMove, { passive: true });
    document.addEventListener('touchend', onEnd);
    return () => {
      document.removeEventListener('touchmove', onMove);
      document.removeEventListener('touchend', onEnd);
    };
  }, [isDragging, moveDrag, endDrag]);

  return { position, isDragging, hasMoved, handleMouseDown, handleTouchStart };
}
