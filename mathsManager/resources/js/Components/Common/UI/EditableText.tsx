import { useRef, useEffect } from 'react';
import { Pencil } from 'lucide-react';

interface Props {
  value: string;
  onChange: (v: string) => void;
  isEditing: boolean;
  onDoubleClick: () => void;
  onBlur: () => void;
  multiline?: boolean;
  className?: string;
  placeholder?: string;
}

const sharedEditClass =
  'w-full bg-transparent border border-dashed border-teacher-color/50 rounded px-1 outline-none focus:border-teacher-color resize-none';

export default function EditableText({
  value,
  onChange,
  isEditing,
  onDoubleClick,
  onBlur,
  multiline = false,
  className = '',
  placeholder = '',
}: Props) {
  const ref = useRef<HTMLInputElement & HTMLTextAreaElement>(null);

  useEffect(() => {
    if (isEditing) ref.current?.focus();
  }, [isEditing]);

  if (isEditing) {
    if (multiline) {
      return (
        <textarea
          ref={ref as React.RefObject<HTMLTextAreaElement>}
          value={value}
          onChange={(e) => onChange(e.target.value)}
          onBlur={onBlur}
          rows={4}
          className={`${sharedEditClass} ${className}`}
          placeholder={placeholder}
        />
      );
    }
    return (
      <input
        ref={ref as React.RefObject<HTMLInputElement>}
        value={value}
        onChange={(e) => onChange(e.target.value)}
        onBlur={onBlur}
        onKeyDown={(e) => e.key === 'Enter' && onBlur()}
        className={`${sharedEditClass} text-center ${className}`}
        placeholder={placeholder}
      />
    );
  }

  return (
    <span
      onDoubleClick={onDoubleClick}
      title="Double-cliquer pour modifier"
      className={`group inline-flex items-center gap-1 cursor-text underline decoration-dashed decoration-teacher-color/40 underline-offset-2 hover:decoration-teacher-color px-1 ${className}`}
    >
      {value || <span className="opacity-40 not-italic">{placeholder}</span>}
      <Pencil
        size={10}
        className="opacity-0 group-hover:opacity-60 text-teacher-color flex-shrink-0 transition-opacity"
      />
    </span>
  );
}
