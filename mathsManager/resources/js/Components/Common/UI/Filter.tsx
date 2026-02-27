import { useState, useRef } from 'react';
import { Filter as FilterIcon } from 'lucide-react';
import FilterDropdown, { FilterOption } from '@/Components/Common/UI/FilterDropdown';

export type { FilterOption };

interface Props {
  options: FilterOption[];
  value: string;
  onChange: (value: string) => void;
  isActive?: boolean;
  disabled?: boolean;
  activeClassName?: string;
}

export default function Filter({
  options,
  value,
  onChange,
  isActive = false,
  disabled = false,
  activeClassName = 'bg-tertiary-color border-tertiary-color text-white',
}: Props) {
  const [isOpen, setIsOpen] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);

  return (
    <div className="relative inline-block" ref={containerRef}>
      <button
        type="button"
        onClick={() => setIsOpen(!isOpen)}
        className={`p-2 border-2 rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed ${
          isActive
            ? activeClassName
            : 'border-border-color bg-secondary-color text-text-gray hover:text-tertiary-color hover:border-tertiary-color'
        }`}
        title="Filtrer"
        disabled={disabled}
      >
        <FilterIcon size={20} />
      </button>

      <FilterDropdown
        options={options}
        value={value}
        onChange={onChange}
        isOpen={isOpen}
        onClose={() => setIsOpen(false)}
        containerRef={containerRef}
      />
    </div>
  );
}
