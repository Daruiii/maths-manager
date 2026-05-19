import { Search, X } from 'lucide-react';
import { InputHTMLAttributes } from 'react';

interface Props extends InputHTMLAttributes<HTMLInputElement> {
  onClear?: () => void;
  // Allow custom focus color (e.g., focus:border-teacher-color)
  focusRingClass?: string;
  // Optional components to render alongside the search input
  filter?: React.ReactNode;
  sort?: React.ReactNode;
}

export default function SearchBar({
  onClear,
  className = '',
  focusRingClass = 'focus:border-tertiary-color focus:ring-tertiary-color',
  filter,
  sort,
  value,
  ...props
}: Props) {
  return (
    <div className={`flex items-center gap-2 ${className}`}>
      <div className="relative flex-1">
        <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-text-gray" size={15} />
        <input
          type="text"
          value={value}
          className={`w-full h-10 pl-9 pr-9 bg-secondary-color border border-border-color rounded-xl text-sm transition-all focus:outline-none focus:ring-1 focus:ring-teacher-color/30 ${focusRingClass}`}
          {...props}
        />
        {value && onClear && (
          <button
            type="button"
            onClick={onClear}
            className="absolute right-3 top-1/2 -translate-y-1/2 text-text-gray hover:text-text-color"
          >
            <X size={16} />
          </button>
        )}
      </div>
      {filter}
      {sort}
    </div>
  );
}
