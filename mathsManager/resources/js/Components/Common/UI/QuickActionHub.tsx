import { useState, useRef, useCallback, useEffect } from 'react';
import { Plus } from 'lucide-react';
import { useQuickActions } from '@/Hooks/UI/useQuickActions';
import { useDraggable } from '@/Hooks/UI/useDraggable';
import QuickActionItem from '@/Components/Common/UI/QuickActionItem';

const MENU_WIDTH = 224;
const HEADER_HEIGHT = 72;
const FAB_SIZE = 48;

export default function QuickActionHub() {
  const [isOpen, setIsOpen] = useState(false);
  const closeTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  const { position, isDragging, hasMoved, handleMouseDown, handleTouchStart } = useDraggable({
    storageKey: 'quick-action-hub-position-v2',
    topMargin: 80,
    onDragStart: () => setIsOpen(false),
  });

  const actions = useQuickActions();

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
          className={`absolute flex flex-col gap-0.5 min-w-[200px] overflow-y-auto overflow-x-hidden pb-2 ${
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
          <Plus size={20} />
        </button>
      </div>
    </div>
  );
}
