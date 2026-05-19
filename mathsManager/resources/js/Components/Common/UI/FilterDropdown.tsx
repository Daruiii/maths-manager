import React, { useEffect } from 'react';

export interface FilterOption {
  value: string;
  label: string;
}

interface Props {
  options: FilterOption[];
  value: string;
  onChange: (value: string) => void;
  isOpen: boolean;
  onClose: () => void;
  containerRef: React.RefObject<React.ElementRef<'div'> | null>;
  activeClassName?: string;
}

export default function FilterDropdown({
  options,
  value,
  onChange,
  isOpen,
  onClose,
  containerRef,
  activeClassName = 'bg-tertiary-color/10 text-tertiary-color',
}: Props) {
  useEffect(() => {
    const handleClickOutside = (event: React.MouseEvent | MouseEvent) => {
      // If the click is inside the container (which includes the toggle button and the dropdown), do nothing
      if (containerRef.current && containerRef.current.contains(event.target as Element)) {
        return;
      }
      onClose();
    };

    if (isOpen) {
      document.addEventListener('mousedown', handleClickOutside);
    }

    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, [isOpen, onClose, containerRef]);

  if (!isOpen) return null;

  return (
    <div className="absolute right-0 top-full mt-2 w-56 bg-surface-color border-2 border-border-color rounded-2xl shadow-xl z-30 p-2 flex flex-col gap-1">
      {options.map((option) => (
        <button
          key={option.value}
          onClick={() => {
            onChange(option.value);
            onClose();
          }}
          className={`w-full text-left px-3 py-2 text-sm font-bold rounded-xl transition-colors ${
            value === option.value
              ? activeClassName
              : 'text-text-gray hover:bg-secondary-color hover:text-text-color'
          }`}
        >
          {option.label}
        </button>
      ))}
    </div>
  );
}
