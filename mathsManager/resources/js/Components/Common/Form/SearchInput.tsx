import { useRef } from 'react';
import { Search, X } from 'lucide-react';

interface Props {
  value: string;
  onChange: (value: string) => void;
  placeholder?: string;
  className?: string;
}

export default function SearchInput({
  value,
  onChange,
  placeholder = 'Rechercher…',
  className = '',
}: Props) {
  const inputRef = useRef<HTMLInputElement>(null);

  return (
    <div className={`relative ${className}`}>
      <Search
        size={12}
        className="absolute left-3 top-1/2 -translate-y-1/2 text-text-gray pointer-events-none"
      />
      <input
        ref={inputRef}
        type="text"
        value={value}
        onChange={(e) => onChange(e.target.value)}
        placeholder={placeholder}
        className="w-full pl-8 pr-7 py-1.5 text-xs border border-border-color bg-secondary-color rounded-xl text-text-color placeholder:text-text-gray focus:outline-none focus:border-teacher-color/50 transition-colors"
      />
      {value && (
        <button
          type="button"
          onClick={() => {
            onChange('');
            inputRef.current?.focus();
          }}
          className="absolute right-2.5 top-1/2 -translate-y-1/2 text-text-gray hover:text-text-color"
        >
          <X size={11} />
        </button>
      )}
    </div>
  );
}
