import { useState, useRef, useCallback, useEffect } from 'react';
import { Zap, X } from 'lucide-react';
import { useQuickActions } from '@/Hooks/UI/useQuickActions';
import { useDraggable } from '@/Hooks/UI/useDraggable';
import QuickActionItem from '@/Components/Common/UI/QuickActionItem';

const MENU_WIDTH = 224;
const HEADER_HEIGHT = 72;
const FAB_SIZE = 48;

interface Props {
  correctionCount?: number;
  whitelistCount?: number;
}

export default function QuickActionHub({ correctionCount = 0, whitelistCount = 0 }: Props) {
  const [isOpen, setIsOpen] = useState(false);
  const closeTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  const { position, isDragging, hasMoved, handleMouseDown, handleTouchStart } = useDraggable({
    storageKey: 'quick-action-hub-position',
    topMargin: 80,
    onDragStart: () => setIsOpen(false),
  });

  const actions = useQuickActions({ correctionCount, whitelistCount });
  const totalBadge = correctionCount + whitelistCount;

  const [windowSize, setWindowSize] = useState({
    width: window.innerWidth,
    height: window.innerHeight,
  });

  useEffect(() => {
    const onResize = () => setWindowSize({ width: window.innerWidth, height: window.innerHeight });
    window.addEventListener('resize', onResize);
    return () => {
      window.removeEventListener('resize', onResize);
      if (closeTimerRef.current) clearTimeout(closeTimerRef.current);
    };
  }, []);

  const handleMouseEnter = useCallback(() => {
    if (isDragging) return;
    if (closeTimerRef.current) clearTimeout(closeTimerRef.current);
    setIsOpen(true);
  }, [isDragging]);

  const handleMouseLeave = useCallback(() => {
    closeTimerRef.current = setTimeout(() => setIsOpen(false), 200);
  }, []);

  const handleTriggerClick = () => {
    if (!hasMoved) setIsOpen((o) => !o);
  };

  const opensUpward = position.y - HEADER_HEIGHT > windowSize.height - position.y - FAB_SIZE;
  const menuAlignRight = position.x + MENU_WIDTH < windowSize.width;

  return (
    <div
      className="fixed z-40 select-none"
      style={{ left: position.x, top: position.y }}
      onMouseEnter={handleMouseEnter}
      onMouseLeave={handleMouseLeave}
    >
      {/* Actions menu */}
      {isOpen && (
        <div
          className={`absolute flex flex-col gap-0.5 min-w-[200px] overflow-y-auto pb-2 ${
            opensUpward ? 'bottom-14' : 'top-14'
          } ${menuAlignRight ? 'left-0' : 'right-0'}`}
          style={{
            maxHeight: opensUpward
              ? position.y - HEADER_HEIGHT - 8
              : windowSize.height - position.y - FAB_SIZE - 8,
          }}
        >
          {actions.map((action, i) => (
            <QuickActionItem
              key={action.id}
              action={action}
              animationDelay={i * 35}
              onNavigate={() => setIsOpen(false)}
            />
          ))}
        </div>
      )}

      {/* FAB trigger */}
      <div className="relative">
        {!isOpen && totalBadge > 0 && (
          <span className="absolute inset-0 rounded-full animate-ping bg-teacher-color/30 pointer-events-none" />
        )}

        <button
          onMouseDown={handleMouseDown}
          onTouchStart={handleTouchStart}
          onClick={handleTriggerClick}
          aria-label="Actions rapides"
          className={`
            relative w-12 h-12 rounded-full flex items-center justify-center shadow-lg
            bg-teacher-color text-white transition-all duration-200
            hover:brightness-110 active:scale-95
            ${isDragging ? 'cursor-grabbing scale-95 shadow-xl' : 'cursor-grab'}
            ${isOpen ? 'rotate-45' : 'rotate-0'}
          `}
        >
          {isOpen ? <X size={20} /> : <Zap size={20} />}
          {!isOpen && totalBadge > 0 && (
            <span className="absolute -top-1 -right-1 text-[10px] font-bold bg-error-color text-white w-4 h-4 flex items-center justify-center rounded-full">
              {totalBadge > 9 ? '9+' : totalBadge}
            </span>
          )}
        </button>
      </div>
    </div>
  );
}
