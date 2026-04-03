import { useState, useRef, useEffect } from 'react';
import { createPortal } from 'react-dom';
import { ChevronDown, Check } from 'lucide-react';

export interface SelectOption {
  value: string;
  label: string;
}

interface Props {
  value: string;
  onChange: (value: string) => void;
  options: SelectOption[];
  placeholder?: string;
  searchable?: boolean;
  searchPlaceholder?: string;
  size?: 'sm' | 'md';
  className?: string;
}

const SIZE = {
  md: {
    trigger: 'px-3 py-2 text-sm rounded-2xl',
    dropdown: 'rounded-2xl',
    item: 'px-3 py-2 text-sm',
  },
  sm: {
    trigger: 'px-2 py-1 text-xs rounded-lg',
    dropdown: 'rounded-xl',
    item: 'px-2.5 py-1.5 text-xs',
  },
};

export default function Select({
  value,
  onChange,
  options,
  placeholder = '—',
  searchable = false,
  searchPlaceholder = 'Rechercher…',
  size = 'md',
  className = '',
}: Props) {
  const [open, setOpen] = useState(false);
  const [search, setSearch] = useState('');
  const [menuStyle, setMenuStyle] = useState<{ top: number; left: number; width: number } | null>(
    null
  );
  const ref = useRef<HTMLDivElement>(null);
  const triggerButtonRef = useRef<HTMLButtonElement>(null);
  const triggerDivRef = useRef<HTMLDivElement>(null);
  const dropdownRef = useRef<HTMLDivElement>(null);
  const searchRef = useRef<HTMLInputElement>(null);

  const s = SIZE[size];
  const selected = options.find((o) => o.value === value);
  const filtered =
    searchable && search.trim()
      ? options.filter((o) => o.label.toLowerCase().includes(search.toLowerCase()))
      : options;

  useEffect(() => {
    function handleClickOutside(e: MouseEvent) {
      const target = e.target as Node;
      if (ref.current?.contains(target) || dropdownRef.current?.contains(target)) {
        return;
      }

      if (ref.current && !ref.current.contains(target)) {
        setOpen(false);
        setSearch('');
      }
    }
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  useEffect(() => {
    if (!open) {
      setMenuStyle(null);
      return;
    }

    function updateMenuPosition() {
      const trigger = triggerButtonRef.current ?? triggerDivRef.current;
      if (!trigger) return;

      const rect = trigger.getBoundingClientRect();
      const sideMargin = 8;
      const viewportWidth = window.innerWidth;

      const searchableExtraWidth = searchable ? 72 : 0;
      const desiredWidth = rect.width + searchableExtraWidth;
      const maxComfortWidth = searchable ? 340 : rect.width;
      const width = Math.min(
        Math.max(rect.width, desiredWidth),
        maxComfortWidth,
        viewportWidth - sideMargin * 2
      );

      // Alignement par la droite pour ouvrir un peu vers la gauche si le menu est plus large.
      let left = rect.right - width;
      if (left < sideMargin) left = sideMargin;
      if (left + width > viewportWidth - sideMargin) {
        left = viewportWidth - sideMargin - width;
      }

      setMenuStyle({
        top: rect.bottom + 4,
        left,
        width,
      });
    }

    updateMenuPosition();
    window.addEventListener('resize', updateMenuPosition);
    window.addEventListener('scroll', updateMenuPosition, true);

    return () => {
      window.removeEventListener('resize', updateMenuPosition);
      window.removeEventListener('scroll', updateMenuPosition, true);
    };
  }, [open, searchable]);

  function handleOpen() {
    setOpen(true);
    setSearch('');
    if (searchable) setTimeout(() => searchRef.current?.focus(), 20);
  }

  const triggerBase = `w-full flex items-center justify-between gap-2 font-comfortaa border-2 bg-surface-color shadow-sm transition-all duration-200 ${s.trigger}`;

  const chevron = (
    <ChevronDown
      size={size === 'sm' ? 10 : 12}
      strokeWidth={2.5}
      className={`shrink-0 text-text-gray transition-transform duration-150 ${open ? 'rotate-180' : ''}`}
    />
  );

  return (
    <div ref={ref} className={`relative ${className}`}>
      {searchable && open ? (
        <div ref={triggerDivRef} className={`${triggerBase} border-border-color`}>
          <input
            ref={searchRef}
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder={selected?.value ? selected.label : searchPlaceholder}
            className={`flex-1 min-w-0 bg-transparent text-text-color placeholder:text-text-gray/50 focus:outline-none focus:ring-0 border-none p-0 m-0 font-comfortaa leading-none ${size === 'sm' ? 'text-xs' : 'text-sm'}`}
          />
          {chevron}
        </div>
      ) : (
        <button
          ref={triggerButtonRef}
          type="button"
          onClick={handleOpen}
          className={`${triggerBase} border-border-color text-left hover:border-tertiary-color/50 focus:outline-none focus:border-tertiary-color`}
        >
          <span
            className={
              selected && selected.value !== ''
                ? 'flex-1 min-w-0 text-text-color truncate'
                : 'flex-1 min-w-0 text-text-gray'
            }
          >
            {selected ? selected.label : placeholder}
          </span>
          {chevron}
        </button>
      )}

      {open &&
        menuStyle &&
        createPortal(
          <div
            ref={dropdownRef}
            style={{
              position: 'fixed',
              top: menuStyle.top,
              left: menuStyle.left,
              width: menuStyle.width,
            }}
            className={`z-[80] bg-surface-color border-2 border-border-color shadow-lg overflow-hidden ${s.dropdown}`}
          >
            <div className="max-h-52 overflow-y-auto overflow-x-hidden custom-scrollbar py-1">
              {filtered.length === 0 ? (
                <p className={`${s.item} text-text-gray/60 italic`}>Aucun résultat</p>
              ) : (
                filtered.map((opt) => (
                  <button
                    key={opt.value}
                    type="button"
                    onClick={() => {
                      onChange(opt.value);
                      setOpen(false);
                      setSearch('');
                    }}
                    className={[
                      `w-full min-w-0 flex items-center justify-between gap-2 font-comfortaa text-left transition-colors ${s.item}`,
                      value === opt.value
                        ? 'text-teacher-color bg-teacher-color/5'
                        : 'text-text-color hover:bg-teacher-color/10',
                    ].join(' ')}
                  >
                    <span className="flex-1 min-w-0 truncate">{opt.label}</span>
                    {value === opt.value && (
                      <Check size={size === 'sm' ? 9 : 11} className="shrink-0 ml-1" />
                    )}
                  </button>
                ))
              )}
            </div>
          </div>,
          document.body
        )}
    </div>
  );
}
