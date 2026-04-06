import { ReactNode } from 'react';
import { LucideIcon } from 'lucide-react';

export interface FilterSelectOption {
  value: string;
  label: string;
}

interface FilterSectionProps {
  icon: LucideIcon;
  action?: ReactNode;
  children: ReactNode;
}

export function FilterSection({ icon: Icon, action, children }: FilterSectionProps) {
  return (
    <div className="relative rounded-xl border border-border-color bg-secondary-color/40 p-2.5 space-y-2 overflow-hidden">
      <Icon
        size={80}
        className="absolute -bottom-1 right-1 rotate-12 text-teacher-color/5 pointer-events-none z-0"
      />
      <div className="relative z-10 space-y-2">
        {action && <div className="flex justify-end">{action}</div>}
        {children}
      </div>
    </div>
  );
}

interface FilterSelectProps {
  label: string;
  icon: LucideIcon;
  value: string;
  onChange: (value: string) => void;
  options: FilterSelectOption[];
  disabled?: boolean;
  searchable?: boolean;
}

export function FilterSelect({
  label,
  icon: Icon,
  value,
  onChange,
  options,
  disabled,
  searchable = false,
}: FilterSelectProps) {
  const inputClass = `text-xs px-2 py-1.5 rounded-lg border-2 bg-secondary-color transition-colors ${
    value ? 'border-teacher-color text-text-color' : 'border-border-color text-text-gray'
  }`;

  if (searchable) {
    const searchableOptions = options.filter((o) => o.value && !o.value.startsWith('__group__'));
    const displayValue =
      searchableOptions.find((o) => o.value === value)?.label.replace(/^[—–\s]+/, '') ?? '';
    const listId = `filter-search-${label.toLowerCase().replace(/\s+/g, '-')}`;

    return (
      <label className="flex flex-col gap-1 text-xs text-text-gray">
        <span className="flex items-center gap-1 text-xs font-medium text-text-gray">
          <Icon size={12} /> {label}
        </span>
        <input
          key={displayValue}
          defaultValue={displayValue}
          type="search"
          list={listId}
          disabled={disabled}
          placeholder={options[0]?.label ?? '—'}
          onChange={(e) => {
            const text = e.target.value;
            if (!text) {
              onChange('');
              return;
            }
            const match = searchableOptions.find((o) => o.label.replace(/^[—–\s]+/, '') === text);
            if (match) onChange(match.value);
          }}
          className={`${inputClass} disabled:cursor-not-allowed disabled:opacity-60`}
        />
        <datalist id={listId}>
          {searchableOptions.map((o) => (
            <option key={o.value} value={o.label.replace(/^[—–\s]+/, '')} />
          ))}
        </datalist>
      </label>
    );
  }

  return (
    <label className="flex flex-col gap-1 text-xs text-text-gray">
      <span className="flex items-center gap-1 text-xs font-medium text-text-gray">
        <Icon size={12} /> {label}
      </span>
      <select
        value={value}
        onChange={(e) => onChange(e.target.value)}
        disabled={disabled}
        className={`${inputClass} cursor-pointer disabled:cursor-not-allowed disabled:opacity-60`}
      >
        {options.map((opt) => (
          <option key={opt.value} value={opt.value}>
            {opt.label}
          </option>
        ))}
      </select>
    </label>
  );
}

interface FilterInputProps {
  label: string;
  icon: LucideIcon;
  value: string;
  placeholder?: string;
  onChange: (value: string) => void;
  type?: 'text' | 'search';
  listId?: string;
  suggestions?: string[];
}

export function FilterInput({
  label,
  icon: Icon,
  value,
  placeholder,
  onChange,
  type = 'text',
  listId,
  suggestions,
}: FilterInputProps) {
  const resolvedListId =
    listId ??
    (suggestions && suggestions.length > 0
      ? `list-${label.toLowerCase().replace(/\s+/g, '-')}`
      : undefined);

  return (
    <label className="flex flex-col gap-1 text-xs text-text-gray">
      <span className="flex items-center gap-1 text-xs font-medium text-text-gray">
        <Icon size={12} /> {label}
      </span>
      <input
        value={value}
        onChange={(e) => onChange(e.target.value)}
        placeholder={placeholder}
        type={type}
        list={resolvedListId}
        className={`text-xs px-2 py-1.5 rounded-lg border-2 bg-secondary-color transition-colors ${
          value ? 'border-teacher-color text-text-color' : 'border-border-color text-text-gray'
        }`}
      />
      {resolvedListId && suggestions && suggestions.length > 0 && (
        <datalist id={resolvedListId}>
          {suggestions.map((suggestion) => (
            <option key={suggestion} value={suggestion} />
          ))}
        </datalist>
      )}
    </label>
  );
}

interface FilterToggleProps {
  label: string;
  icon: LucideIcon;
  checked: boolean;
  onToggle: () => void;
}

export function FilterToggle({ label, icon: Icon, checked, onToggle }: FilterToggleProps) {
  return (
    <button
      type="button"
      onClick={onToggle}
      className={`flex items-center justify-between gap-2 text-xs px-3 py-2 rounded-lg border-2 transition-colors ${
        checked
          ? 'border-error-color bg-error-color/10 text-error-color'
          : 'border-border-color bg-secondary-color text-text-gray hover:border-error-color hover:text-error-color'
      }`}
    >
      <span className="flex items-center gap-1">
        <Icon size={12} /> {label}
      </span>
      <span className="text-xs font-medium">{checked ? 'ON' : 'OFF'}</span>
    </button>
  );
}
