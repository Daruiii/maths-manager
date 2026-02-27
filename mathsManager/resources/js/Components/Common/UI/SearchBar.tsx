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
        <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-text-gray" size={18} />
        <input
          type="text"
          value={value}
          className={`w-full pl-10 pr-10 py-2 bg-secondary-color border-2 border-border-color rounded-xl text-sm transition-colors ${focusRingClass}`}
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
